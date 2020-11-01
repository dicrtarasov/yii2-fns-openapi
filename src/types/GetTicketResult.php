<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 02:10:35
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Результат проверки чека.
 */
class GetTicketResult
{
    /** @var int Код возврата: 200; 400; 406; 503 */
    public $Code;

    /**
     * @var string Сообщение пользователю
     * - если 200, то "Отправленные данные корректны"
     * - если 400, то "Формат отправленных данных некорректен"
     * - если 406, то "Данные не прошли проверку"
     * - если 503, то "Сервис недоступен".
     */
    public $Message;

    /** @var string содержимое чека */
    public $Ticket;

    /**
     * Загрузка из XML.
     *
     * @param SimpleXMLElement $xml
     */
    public function loadXml(SimpleXMLElement $xml) : void
    {
        if (isset($xml->Code)) {
            $this->Code = (int)$xml->Code;
        }

        if (isset($xml->Message)) {
            $this->Message = (string)$xml->Message;
        }

        if (isset($xml->Ticket)) {
            $this->Ticket = (string)$xml->Ticket;
        }
    }
}
