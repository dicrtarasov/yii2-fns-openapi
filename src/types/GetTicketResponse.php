<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 01:35:45
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Ответ на запрос проверки или информации чека.
 */
class GetTicketResponse
{
    /** @var GetTicketResult */
    public $Result;

    /** @var ?KktTicketServiceFault */
    public $Fault;

    /**
     * Загрузка из XML.
     *
     * @param SimpleXMLElement $xml
     * @return static
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();

        if (isset($xml->Result)) {
            $self->Result = GetTicketResult::fromXml($xml->Result);
        }

        if (isset($xml->Fault)) {
            $self->Fault = KktTicketServiceFault::fromXml($xml->Fault);
        }

        return $self;
    }
}
