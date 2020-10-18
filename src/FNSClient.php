<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 10:25:30
 */

declare(strict_types = 1);
namespace dicr\fns\openapi;

use dicr\fns\openapi\types\AuthAppInfo;
use dicr\fns\openapi\types\AuthRequest;
use dicr\fns\openapi\types\AuthResponse;
use dicr\fns\openapi\types\AuthServiceFault;
use dicr\fns\openapi\types\GetMessageRequest;
use dicr\fns\openapi\types\GetMessageResponse;
use dicr\fns\openapi\types\Message;
use SoapClient;
use SoapFault;
use yii\base\Component;
use yii\base\InvalidConfigException;

use function stream_context_create;

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
     * @param GetMessageRequest $getMessageRequest
     * @param string $wsdl
     * @return GetMessageResponse
     * @throws SoapFault
     */
    public function getMessage(GetMessageRequest $getMessageRequest, string $wsdl) : GetMessageResponse
    {
        $soapClient = new SoapClient($this->url . $wsdl, [
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'trace' => 1,
            'exceptions' => true,
            'context' => stream_context_create([
                'http' => [
                    'header' => []
                ]
            ]),
            'classmap' => [
                'GetMessageRequest' => GetMessageRequest::class,
                'GetMessageResponse' => GetMessageResponse::class,
                'Message' => Message::class,
                'AuthRequest' => AuthRequest::class,
                'AuthAppInfo' => AuthAppInfo::class,
                'AuthResponse' => AuthResponse::class,
                'AuthServiceFault' => AuthServiceFault::class,
            ]
        ]);

        /** @noinspection PhpUndefinedMethodInspection */
        $ret = $soapClient->GetMessage($getMessageRequest);
        var_dump($ret);
        exit;
    }

    /**
     * @return AuthResponse
     * @throws SoapFault
     */
    public function auth() : AuthResponse
    {
        $getMessageResponse = $this->getMessage(new GetMessageRequest([
            'Message' => new Message([
                'any' => new AuthRequest([
                    'AuthAppInfo' => new AuthAppInfo([
                        'MasterToken' => $this->masterToken
                    ])
                ])
            ])
        ]), '/open-api/AuthService/0.1?wsdl');

        return $getMessageResponse->Message;
    }
}
