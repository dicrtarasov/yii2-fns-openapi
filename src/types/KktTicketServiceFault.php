<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:28:31
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Ошибка сервиса ККТ.
 */
class KktTicketServiceFault
{
    /** @var string Сообщение об ошибке */
    public $Message;

    /**
     * Загрузка из XML.
     *
     * @param SimpleXMLElement $xml
     * @return static
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();
        $self->Message = (string)$xml->Message;

        return $self;
    }
}
