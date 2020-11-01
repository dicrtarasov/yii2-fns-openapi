<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 05:26:05
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\fns\openapi\FNSClient;
use dicr\fns\openapi\types\CheckTicketInfo;
use dicr\fns\openapi\types\TicketInfo;
use dicr\fns\openapi\types\TypeOperation;
use PHPUnit\Framework\TestCase;
use SoapFault;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;

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

        $checkTicketResponse = $fnsClient->checkTicket(new CheckTicketInfo([
            'Sum' => 103800,
            'Date' => '2020-08-01T13:50:00',
            'Fn' => '9280440300813769',
            'TypeOperation' => TypeOperation::INCOME,
            'FiscalDocumentId' => 2545,
            'FiscalSign' => 2820563972
        ]));
    }

}
