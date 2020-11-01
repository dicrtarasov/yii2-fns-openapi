<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 04:59:47
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\fns\openapi\FNSClient;
use dicr\fns\openapi\types\TicketInfo;
use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;
use SoapFault;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;

use function file_get_contents;

/**
 * Class SberbankModuleTest
 */
class NFSClientTest extends TestCase
{
    /**
     * Клиент FNS.
     *
     * @return FNSClient
     * @throws InvalidConfigException
     */
    private static function client() : FNSClient
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::$app->get('fnsClient');
    }

    /**
     * @throws SoapFault
     * @throws Exception
     */
    public function testAuth() : void
    {
        $fnsClient = self::client();
        $token = $fnsClient->authToken();
        self::assertNotEmpty($token);
        echo 'Токен: ' . $token . "\n";
    }

    public function testCheck() : void
    {
        $fnsClient = self::client();

        $checkTicketResponse = $fnsClient->checkTicket(new TicketInfo([
            'Sum' => 103800,
            'Date' => '2020-08-01T13:50:00',
            'Fn' => '9280440300813769',
            'TypeOperation' => TicketInfo::TYPE_OP_INCOME,
            'FiscalDocumentId' => 2545,
            'FiscalSign' => 2820563972
        ]));
    }

    public function testXml() : void
    {
        $content = file_get_contents(__DIR__ . '/xml.xml');

        $xml = new SimpleXMLElement($content, 0, false, 'soap', true);

        $xmlAuthResponse = $xml->children('soap', true)
            ->Body->children('urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiMessageConsumerService/types/1.0')
            ->GetMessageResponse
            ->Message->children('tns', true)
            ->AuthResponse;

        var_dump($xmlAuthResponse);
        exit;

        $dom = new DOMDocument();
        $dom->loadXML($content);
        $xpath = new DOMXPath($dom);

        $xpath->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xpath->registerNamespace('msg',
            'urn://x-artefacts-gnivc-ru/inplat/servin/OpenApiMessageConsumerService/types/1.0');
        $xpath->registerNamespace('tns', 'urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0');

        var_dump($xpath->query('soap:Body/msg:GetMessageResponse/msg:Message/tns:AuthResponse'));
        exit;

        var_dump($els);
        exit;
    }

}
