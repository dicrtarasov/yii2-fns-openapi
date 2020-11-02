<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 00:31:42
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use SimpleXMLElement;

/**
 * Class AuthResponse
 */
class AuthResponse
{
    /** @var ?AuthResponseResult */
    public $Result;

    /** @var ?AuthServiceFault */
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
            $self->Result = AuthResponseResult::fromXml($xml->Result);
        }

        if (isset($xml->Fault)) {
            $self->Fault = AuthServiceFault::fromXml($xml->Fault);
        }

        return $self;
    }
}
