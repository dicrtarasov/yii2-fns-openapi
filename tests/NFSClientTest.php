<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 07:53:19
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\fns\openapi\FNSClient;
use dicr\fns\openapi\types\TicketInfo;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;

/**
 * Class SberbankModuleTest
 */
class NFSClientTest extends TestCase
{
    /**
     * @var array данные тестового чека
     * t=20200801T1350&s=1038.00&fn=9280440300813769&i=2545&fp=2820563972&n=1
     */
    public const TICKET_INFO1 = [
        'Sum' => 103800,
        'Date' => '2020-08-01T13:50:00',
        'Fn' => '9280440300813769',
        'TypeOperation' => TicketInfo::TYPE_OP_INCOME,
        'FiscalDocumentId' => 2545,
        'FiscalSign' => 2820563972
    ];

    /**
     * @var array данные тестового чека
     * t=20200820T1355&s=977.99&fn=9280440300703871&i=17663&fp=3955696418&n=1
     */
    public const TICKET_INFO2 = [
        'Sum' => 97799,
        'Date' => '2020-08-20T13:55:00',
        'Fn' => '9280440300703871',
        'TypeOperation' => TicketInfo::TYPE_OP_INCOME,
        'FiscalDocumentId' => 17663,
        'FiscalSign' => 3955696418
    ];

    /** @var array данные моего чека */
    public const TICKET_INFO3 = [
        'Sum' => 79800,
        'Date' => '2017-09-26T18:25:00',
        'Fn' => '8710000100620128',
        'TypeOperation' => TicketInfo::TYPE_OP_INCOME,
        'FiscalDocumentId' => 58518,
        'FiscalSign' => 957304760
    ];

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
     * @throws Exception
     */
    public function testAuth() : void
    {
        $fnsClient = self::client();
        $token = $fnsClient->authToken();
        self::assertNotEmpty($token);
        echo 'Токен: ' . $token . "\n";
    }

    /**
     * @throws Exception
     */
    public function testCheck() : void
    {
        $fnsClient = self::client();
        $checkTicketResult = $fnsClient->checkTicket(new TicketInfo(self::TICKET_INFO2));

        self::assertSame(200, $checkTicketResult->Code);
        echo 'Код: ' . $checkTicketResult->Code . "\n";
        echo 'Сообщение: ' . $checkTicketResult->Message . "\n";
    }

    /**
     * @throws Exception
     */
    public function testGet() : void
    {
        $fnsClient = self::client();
        $checkTicketResult = $fnsClient->getTicket(new TicketInfo(self::TICKET_INFO2));
        self::assertIsArray($checkTicketResult->Ticket);
    }
}
