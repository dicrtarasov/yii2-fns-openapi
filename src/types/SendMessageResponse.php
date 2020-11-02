<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 02:03:39
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Class SendMessageResponse
 */
class SendMessageResponse
{
    /** @var string Идентификатор сообщения */
    public $MessageId;

    /**
     * Создание из XML.
     *
     * @param SimpleXMLElement $xml
     * @return static
     * @noinspection PhpUndefinedFieldInspection
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();
        $self->MessageId = (string)$xml->MessageId;

        return $self;
    }
}
