<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 01:39:18
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Class GetMessageResponse
 */
class GetMessageResponse
{
    /**
     * @var ?string
     * @see ProcessingStatuses
     */
    public $ProcessingStatus;

    /** @var Message */
    public $Message;

    /**
     * Создает из XML.
     *
     * @param SimpleXMLElement $xml
     * @return static
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();

        if (isset($xml->ProcessingStatus)) {
            $self->ProcessingStatus = (string)$xml->ProcessingStatus;
        }

        if (isset($xml->Message)) {
            $self->Message = Message::fromXml($xml->Message);
        }

        return $self;
    }
}
