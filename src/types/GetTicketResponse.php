<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 02:10:27
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
     */
    public function loadXml(SimpleXMLElement $xml) : void
    {
        if (isset($xml->Result)) {
            $this->Result = new GetTicketResult();
            $this->Result->loadXml($xml->Result);
        }

        if (isset($xml->Fault)) {
            $this->Fault = new KktTicketServiceFault();
            $this->Fault->loadXml($xml->Fault);
        }
    }
}
