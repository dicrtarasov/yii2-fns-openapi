<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 02:10:41
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
     */
    public function loadXml(SimpleXMLElement $xml) : void
    {
        if (isset($xml->Message)) {
            $this->Message = (string)$xml->Message;
        }
    }
}
