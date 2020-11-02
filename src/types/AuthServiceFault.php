<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 23:44:19
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Ошибка сервиса сообщений.
 */
class AuthServiceFault
{
    /** @var string Сообщение об ошибке */
    public $Message;

    /**
     * Загрузка из XML.
     *
     * @param SimpleXMLElement $xml
     * @return static
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();

        if (isset($xml->Message)) {
            $self->Message = (string)$xml->Message;
        }

        return $self;
    }
}
