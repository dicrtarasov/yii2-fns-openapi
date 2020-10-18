<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 10:09:21
 */

declare(strict_types = 1);
namespace dicr\tests;

use dicr\fns\openapi\FNSClient;
use PHPUnit\Framework\TestCase;
use SoapFault;
use Yii;
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
     * @throws InvalidConfigException
     * @throws SoapFault
     */
    public function testAuth() : void
    {
        $fnsClient = self::client();
        $ret = $fnsClient->auth();
        var_dump($ret);
        exit;
    }

}
