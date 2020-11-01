<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 02:09:09
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Class AuthResponseResult
 */
class AuthResponseResult
{
    /** @var string Токен сгенерированный для внешнего приложения */
    public $Token;

    /** @var string Y-m-d\TH:i:s\Z Дата и время истечения сгенерированного токена */
    public $ExpireTime;

    /**
     * Загрузка из XML.
     *
     * @param SimpleXMLElement $xml
     */
    public function loadXml(SimpleXMLElement $xml) : void
    {
        if (isset($xml->Token)) {
            $this->Token = (string)$xml->Token;
        }

        if (isset($xml->ExpireTime)) {
            $this->ExpireTime = (string)$xml->ExpireTime;
        }
    }
}
