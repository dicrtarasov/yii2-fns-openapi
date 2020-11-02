<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 16:03:57
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\fns\openapi\FNSClient;
use dicr\fns\openapi\types\CheckTicketInfo;
use dicr\fns\openapi\types\GetTicketInfo;
use dicr\fns\openapi\types\TypeOperation;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;

use function print_r;

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
        'TypeOperation' => TypeOperation::INCOME,
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
        'TypeOperation' => TypeOperation::INCOME,
        'FiscalDocumentId' => 17663,
        'FiscalSign' => 3955696418
    ];

    /** @var array данные моего чека */
    public const TICKET_INFO3 = [
        'Sum' => 79800,
        'Date' => '2017-09-26T18:25:00',
        'Fn' => '8710000100620128',
        'TypeOperation' => TypeOperation::INCOME,
        'FiscalDocumentId' => 58518,
        'FiscalSign' => 957304760
    ];

    /** @var array чек клиента */
    public const TICKET_INFO4 = [
        'Sum' => 200000,
        'Date' => '2020-10-14T09:45:00',
        'Fn' => '9281000100087974',
        'TypeOperation' => TypeOperation::INCOME,
        'FiscalDocumentId' => 19469,
        'FiscalSign' => 4159188716
    ];

    /** @var array чек клиента */
    public const TICKET_INFO5 = [
        'Sum' => 99100,
        'Date' => '2020-10-03T15:27:00',
        'Fn' => '9280440300430432',
        'TypeOperation' => TypeOperation::INCOME,
        'FiscalDocumentId' => 29127,
        'FiscalSign' => 266252041
    ];

    /** @var array */
    public const TICKET_INFO = self::TICKET_INFO5;

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
        $checkTicketResult = $fnsClient->checkTicket(new CheckTicketInfo(self::TICKET_INFO));
        self::assertNotEmpty($checkTicketResult->Code);

        echo 'Код: ' . $checkTicketResult->Code . "\n";
        echo 'Сообщение: ' . $checkTicketResult->Message . "\n";
    }

    /**
     * @throws Exception
     */
    public function testGet() : void
    {
        $fnsClient = self::client();
        $getTicketResult = $fnsClient->getTicket(new GetTicketInfo(self::TICKET_INFO));

        self::assertNotEmpty($getTicketResult->Code);

        echo 'Код: ' . $getTicketResult->Code . "\n";
        echo 'Сообщение: ' . $getTicketResult->Message . "\n";
        echo 'Чек: ' . print_r($getTicketResult->Ticket, true) . "\n";
    }
}
