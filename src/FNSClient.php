<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 06:20:44
 */

declare(strict_types = 1);
namespace dicr\fns\openapi;

use dicr\fns\openapi\types\AuthAppInfo;
use dicr\fns\openapi\types\AuthenticationFault;
use dicr\fns\openapi\types\AuthRequest;
use dicr\fns\openapi\types\AuthResponse;
use dicr\fns\openapi\types\AuthServiceFault;
use dicr\fns\openapi\types\CheckTicketInfo;
use dicr\fns\openapi\types\CheckTicketRequest;
use dicr\fns\openapi\types\CheckTicketResponse;
use dicr\fns\openapi\types\CheckTicketResult;
use dicr\fns\openapi\types\GeoInfo;
use dicr\fns\openapi\types\GetMessageRequest;
use dicr\fns\openapi\types\GetMessageResponse;
use dicr\fns\openapi\types\GetTicketInfo;
use dicr\fns\openapi\types\GetTicketRequest;
use dicr\fns\openapi\types\GetTicketResponse;
use dicr\fns\openapi\types\GetTicketResult;
use dicr\fns\openapi\types\KktTicketServiceFault;
use dicr\fns\openapi\types\Message;
use dicr\fns\openapi\types\MessageNotFoundFault;
use dicr\fns\openapi\types\SendMessageRequest;
use dicr\fns\openapi\types\SendMessageResponse;
use dicr\fns\openapi\types\TicketInfo;
use SimpleXMLElement;
use SoapClient;
use SoapFault;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;

use function stream_context_create;
use function strtotime;

use const SOAP_1_1;
use const SOAP_COMPRESSION_ACCEPT;
use const SOAP_COMPRESSION_DEFLATE;
use const SOAP_COMPRESSION_GZIP;

/**
 * Клиент ФНС OpenAPI.
 */
class FNSClient extends Component
{
    /** @var string */
    public const API_URL = 'https://openapi.nalog.ru:8090';

    /** @var string[] карта соответствия типов XML SOAP классам PHP */
    public const CLASS_MAP = [
        // запрос-ответ
        'GetMessageRequest' => GetMessageRequest::class,
        'GetMessageResponse' => GetMessageResponse::class,
        'Message' => Message::class,

        // авторизация
        'AuthRequest' => AuthRequest::class,
        'AuthAppInfo' => AuthAppInfo::class,
        'AuthResponse' => AuthResponse::class,
        'AuthServiceFault' => AuthServiceFault::class,

        // проверка чека
        'CheckTicketRequest' => CheckTicketRequest::class,
        'CheckTicketInfo' => CheckTicketInfo::class,
        'CheckTicketResponse' => CheckTicketResponse::class,
        'CheckTicketResult' => CheckTicketResult::class,

        // получение информации по чеку
        'GetTicketRequest' => GetTicketRequest::class,
        'GetTicketInfo' => GetTicketInfo::class,
        'GetTicketResponse' => GetTicketResponse::class,
        'GetTicketResult' => GetTicketResult::class,

        'KktTicketServiceFault' => KktTicketServiceFault::class,
        'GeoInfo' => GeoInfo::class,

        'MessageNotFoundFault' => MessageNotFoundFault::class,
        'AuthenticationFault' => AuthenticationFault::class,
        'SendMessageRequest' => SendMessageRequest::class
    ];

    /** @var string URL API */
    public $url = self::API_URL;

    /** @var string мастер-токен, выданный ФНС */
    public $masterToken;

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init() : void
    {
        parent::init();

        if (empty($this->url)) {
            throw new InvalidConfigException('url');
        }

        if (empty($this->masterToken)) {
            throw new InvalidConfigException('materToken');
        }
    }

    /**
     * Создает SOAP-клиент.
     *
     * @param string $wsdl относительный адрес WSDL
     * @param ?string $token токен авторизации
     * @return SoapClient
     * @throws SoapFault
     */
    public function soapClient(string $wsdl, ?string $token = null) : SoapClient
    {
        // контекст HTTP
        $streamContext = stream_context_create([
            'http' => [
                // HTTP-заголовки авторизации
                'header' => empty($token) ? [] : [
                    'FNS-OpenApi-Token' => $token,
                ]
            ]
        ]);

        ini_set('soap.wsdl_cache_ttl', 0);

        // клиент SOAP
        return new SoapClient($wsdl ? $this->url . $wsdl : null, [
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'trace' => true,
            'exceptions' => true,
            'context' => $streamContext,
            'classmap' => self::CLASS_MAP,
            'soap_version' => SOAP_1_1
        ]);
    }

    /**
     * Получает/возвращает токен авторизации.
     *
     * @return string
     * @throws Exception
     * @throws SoapFault
     */
    public function authToken() : string
    {
        $key = [__CLASS__, $this->url, $this->masterToken];

        $token = Yii::$app->cache->get($key);
        if (empty($token)) {
            $soapClient = $this->soapClient('/open-api/AuthService/0.1?wsdl');

            try {
                /** @noinspection PhpUndefinedMethodInspection */
                /** @var GetMessageResponse $messageResponse */
                $messageResponse = $soapClient->GetMessage(new GetMessageRequest([
                    'Message' => new Message([
                        'any' => new AuthRequest([
                            'AuthAppInfo' => new AuthAppInfo([
                                'MasterToken' => $this->masterToken
                            ])
                        ])
                    ])
                ]));
            } finally {
                Yii::debug('Запрос: ' . $soapClient->__getLastRequest(), __METHOD__);
                Yii::debug('Ответ: ' . $soapClient->__getLastResponse(), __METHOD__);
            }

            $xml = new SimpleXMLElement($messageResponse->Message->any ?? '');

            // парсим ответ
            $authResponse = new AuthResponse();
            $authResponse->loadXml($xml->children('tns', true));

            // получаем токен
            $token = $authResponse->Result->Token ?? null;
            if (empty($token)) {
                throw new Exception('Ошибка получения токена: ' . ($authResponse->Fault->Message ?? ''));
            }

            // сохраняем токен в кеше
            Yii::$app->cache->set(
                $key, $token, strtotime($authResponse->Result->ExpireTime) - time()
            );

            Yii::debug('Получен новый токен: ' . $token . ' до ' . $authResponse->Result->ExpireTime, __METHOD__);
        }

        return $token;
    }

    /**
     * Проверка чека.
     *
     * @param CheckTicketInfo $ticketInfo
     * @return CheckTicketResponse
     * @throws Exception
     * @throws SoapFault
     */
    public function checkTicket(CheckTicketInfo $ticketInfo) : CheckTicketResponse
    {
        $soapClient = $this->soapClient('' /*'/open-api/ais3/KktService/0.1?wsdl'*/, $this->authToken());

        try {
            /** @noinspection PhpUndefinedMethodInspection */
            /** @var SendMessageResponse $messageResponse */
            $messageResponse = $soapClient->SendMessage(new SendMessageRequest([
                'Message' => new Message([
                    'any' => new CheckTicketRequest([
                        'CheckTicketInfo' => $ticketInfo
                    ])
                ])
            ]));
        } finally {
            Yii::debug('Запрос: ' . $soapClient->__getLastRequest(), __METHOD__);
            Yii::debug('Ответ: ' . $soapClient->__getLastResponse(), __METHOD__);
        }

        var_dump($messageResponse);
        exit;

        $xml = new SimpleXMLElement($messageResponse->Message->any ?? '');

        $ticketResponse = new CheckTicketResponse();
        $ticketResponse->loadXml($xml->children('tns', true));

        return $ticketResponse;
    }
}
