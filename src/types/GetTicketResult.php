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
use yii\helpers\Json;

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

    /** @var array содержимое чека */
    public $Ticket;

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
        $self->Code = (int)$xml->Code;
        $self->Message = (string)$xml->Message;

        $ticket = (string)$xml->Ticket;
        if (! empty($ticket)) {
            $self->Ticket = Json::decode($ticket);
        }

        return $self;
    }
}
