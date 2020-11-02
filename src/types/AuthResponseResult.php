<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 00:18:50
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
     * @return static
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();

        if (isset($xml->Token)) {
            $self->Token = (string)$xml->Token;
        }

        if (isset($xml->ExpireTime)) {
            $self->ExpireTime = (string)$xml->ExpireTime;
        }

        return $self;
    }
}
