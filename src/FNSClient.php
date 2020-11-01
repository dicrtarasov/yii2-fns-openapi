<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 07:52:17
 */

declare(strict_types = 1);
namespace dicr\fns\openapi;

use dicr\fns\openapi\types\CheckTicketResult;
use dicr\fns\openapi\types\GetTicketResult;
use dicr\fns\openapi\types\TicketInfo;
use dicr\helper\Html;
use SimpleXMLElement;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

use function ob_get_clean;
use function ob_start;
use function sleep;
use function strtotime;

/**
 * Клиент ФНС OpenAPI.
 *
 * @property-read Client $httpClient
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

    /** @var Client */
    private $_httpClient;

    /**
     * HTTP-клиент.
     *
     * @return Client
     */
    public function getHttpClient() : Client
    {
        if ($this->_httpClient === null) {
            $this->_httpClient = new Client([
                'baseUrl' => $this->url
            ]);
        }

        return $this->_httpClient;
    }

    /**
     * Получает/возвращает токен авторизации.
     *
     * @return string
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function authToken() : string
    {
        $key = [__METHOD__, $this->url, $this->masterToken];
        $token = Yii::$app->cache->get($key);
        if (empty($token)) {
            ob_start();
            ?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                              xmlns:ns="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiMessageConsumerService/types/1.0"
            >
                <soapenv:Header/>
                <soapenv:Body>
                    <ns:GetMessageRequest>
                        <ns:Message>
                            <tns:AuthRequest xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0">
                                <tns:AuthAppInfo>
                                    <tns:MasterToken><?= Html::esc($this->masterToken) ?></tns:MasterToken>
                                </tns:AuthAppInfo>
                            </tns:AuthRequest>
                        </ns:Message>
                    </ns:GetMessageRequest>
                </soapenv:Body>
            </soapenv:Envelope>
            <?php
            $req = $this->httpClient->post('/open-api/AuthService/0.1', ob_get_clean(), [
                'FNS-OpenApi-UserToken' => $this->masterToken,
                'Content-Type' => 'application/xml'
            ]);

            Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
            $res = $req->send();
            Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

            if (! $res->isOk) {
                throw new Exception('HTTP-error: ' . $res->statusCode);
            }

            $xml = new SimpleXMLElement($res->content);

            $xmlAuthResponse = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')
                ->Body->children('urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiMessageConsumerService/types/1.0')
                ->GetMessageResponse
                ->Message->children('urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0')
                ->AuthResponse;

            $token = (string)$xmlAuthResponse->Result->Token;
            $expireTime = (string)$xmlAuthResponse->Result->ExpireTime;

            if (empty($token)) {
                throw new Exception('Ошибка авторизации: ' . ($xmlAuthResponse->Fault->Message ?? ''));
            }

            Yii::debug('Получен новый токен: ' . $token . ' до ' . $expireTime, __METHOD__);

            // сохраняем в кеше
            Yii::$app->cache->set(
                $key, $token, $expireTime ? strtotime($expireTime) - time() : null
            );
        }

        return $token;
    }

    /**
     * Проверка чека.
     *
     * @param TicketInfo $ticketInfo
     * @return CheckTicketResult
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function checkTicket(TicketInfo $ticketInfo) : CheckTicketResult
    {
        // токен авторизации
        $token = $this->authToken();

        // отправляем запрос на создание сообщения
        ob_start();
        ?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                          xmlns:ns="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0"
        >
            <soapenv:Header/>
            <soapenv:Body>
                <ns:SendMessageRequest>
                    <ns:Message>
                        <tns:CheckTicketRequest
                                xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0"
                        >
                            <tns:CheckTicketInfo>
                                <tns:Sum><?= (int)$ticketInfo->Sum ?></tns:Sum>
                                <tns:Date><?= Html::esc($ticketInfo->Date) ?></tns:Date>
                                <tns:Fn><?= Html::esc($ticketInfo->Fn) ?></tns:Fn>
                                <tns:TypeOperation><?= (int)$ticketInfo->TypeOperation ?></tns:TypeOperation>
                                <tns:FiscalDocumentId><?= (int)$ticketInfo->FiscalDocumentId ?></tns:FiscalDocumentId>
                                <tns:FiscalSign><?= (int)$ticketInfo->FiscalSign ?></tns:FiscalSign>
                            </tns:CheckTicketInfo>
                        </tns:CheckTicketRequest>
                    </ns:Message>
                </ns:SendMessageRequest>
            </soapenv:Body>
        </soapenv:Envelope>
        <?php
        $req = $this->httpClient->post('/open-api/ais3/KktService/0.1', ob_get_clean(), [
            'FNS-OpenApi-UserToken' => $this->masterToken,
            'FNS-OpenApi-Token' => $token,
            'Content-Type' => 'application/xml'
        ]);

        Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
        $res = $req->send();
        Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $xml = new SimpleXMLElement($res->content);

        $xmlResponse = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->Body->children('urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0')
            ->SendMessageResponse;

        // код отправленного сообщения
        $messageId = (string)$xmlResponse->MessageId;
        if (empty($messageId)) {
            throw new Exception('Ошибка получения MessageId');
        }

        // ждем 1 секунду
        sleep(1);

        // отправляем запрос на получение ответа
        ob_start();
        ?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                          xmlns:ns="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0"
        >
            <soapenv:Header/>
            <soapenv:Body>
                <ns:GetMessageRequest>
                    <ns:MessageId><?= Html::esc($messageId) ?></ns:MessageId>
                </ns:GetMessageRequest>
            </soapenv:Body>
        </soapenv:Envelope>
        <?php
        $req = $this->httpClient->post('/open-api/ais3/KktService/0.1', ob_get_clean(), [
            'FNS-OpenApi-UserToken' => $this->masterToken,
            'FNS-OpenApi-Token' => $token,
            'Content-Type' => 'application/xml'
        ]);

        Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
        $res = $req->send();
        Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $xml = new SimpleXMLElement($res->content);

        $xmlResponse = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->Body->children('urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0')
            ->GetMessageResponse;

        // проверяем статус обработки сообщения
        $status = (string)$xmlResponse->ProcessingStatus;
        if ($status !== 'COMPLETED') {
            throw new Exception('Ошибка получения данных сообщения');
        }

        $result = new CheckTicketResult();
        $result->loadXml($xmlResponse
            ->Message->children('urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0')
            ->CheckTicketResponse->Result
        );

        return $result;
    }

    /**
     * Получение данных чека.
     *
     * @param TicketInfo $ticketInfo
     * @return GetTicketResult данные чека
     * @throws Exception
     * @noinspection PhpUndefinedFieldInspection
     */
    public function getTicket(TicketInfo $ticketInfo) : GetTicketResult
    {
        // токен авторизации
        $token = $this->authToken();

        // отправляем запрос на создание сообщения
        ob_start();
        ?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                          xmlns:ns="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0"
        >
            <soapenv:Header/>
            <soapenv:Body>
                <ns:SendMessageRequest>
                    <ns:Message>
                        <tns:GetTicketRequest
                                xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0"
                        >
                            <tns:GetTicketInfo>
                                <tns:Sum><?= (int)$ticketInfo->Sum ?></tns:Sum>
                                <tns:Date><?= Html::esc($ticketInfo->Date) ?></tns:Date>
                                <tns:Fn><?= Html::esc($ticketInfo->Fn) ?></tns:Fn>
                                <tns:TypeOperation><?= (int)$ticketInfo->TypeOperation ?></tns:TypeOperation>
                                <tns:FiscalDocumentId><?= (int)$ticketInfo->FiscalDocumentId ?></tns:FiscalDocumentId>
                                <tns:FiscalSign><?= (int)$ticketInfo->FiscalSign ?></tns:FiscalSign>
                            </tns:GetTicketInfo>
                        </tns:GetTicketRequest>
                    </ns:Message>
                </ns:SendMessageRequest>
            </soapenv:Body>
        </soapenv:Envelope>
        <?php
        $req = $this->httpClient->post('/open-api/ais3/KktService/0.1', ob_get_clean(), [
            'FNS-OpenApi-UserToken' => $this->masterToken,
            'FNS-OpenApi-Token' => $token,
            'Content-Type' => 'application/xml'
        ]);

        Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
        $res = $req->send();
        Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $xml = new SimpleXMLElement($res->content);
        $xmlResponse = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->Body->children('urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0')
            ->SendMessageResponse;

        // код отправленного сообщения
        $messageId = (string)$xmlResponse->MessageId;
        if (empty($messageId)) {
            throw new Exception('Ошибка получения MessageId');
        }

        // ждем 1 секунду
        sleep(1);

        // отправляем запрос на получение ответа
        ob_start();
        ?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                          xmlns:ns="urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0"
        >
            <soapenv:Header/>
            <soapenv:Body>
                <ns:GetMessageRequest>
                    <ns:MessageId><?= Html::esc($messageId) ?></ns:MessageId>
                </ns:GetMessageRequest>
            </soapenv:Body>
        </soapenv:Envelope>
        <?php
        $req = $this->httpClient->post('/open-api/ais3/KktService/0.1', ob_get_clean(), [
            'FNS-OpenApi-UserToken' => $this->masterToken,
            'FNS-OpenApi-Token' => $token,
            'Content-Type' => 'application/xml'
        ]);

        Yii::debug('Запрос: ' . $req->toString(), __METHOD__);
        $res = $req->send();
        Yii::debug('Ответ: ' . $res->toString(), __METHOD__);

        if (! $res->isOk) {
            throw new Exception('HTTP-error: ' . $res->statusCode);
        }

        $xml = new SimpleXMLElement($res->content);

        $xmlResponse = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->Body->children('urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiAsyncMessageConsumerService/types/1.0')
            ->GetMessageResponse;

        // проверяем статус обработки сообщения
        $status = (string)$xmlResponse->ProcessingStatus;
        if ($status !== 'COMPLETED') {
            throw new Exception('Ошибка получения данных сообщения');
        }

        $result = new GetTicketResult();
        $result->loadXml($xmlResponse
            ->Message->children('urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0')
            ->CheckTicketResponse->Result
        );

        return $result;
    }
}
