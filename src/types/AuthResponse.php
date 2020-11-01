<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 02:09:14
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
     */
    public function loadXml(SimpleXMLElement $xml) : void
    {
        if (isset($xml->Result)) {
            $this->Result = new AuthResponseResult();
            $this->Result->loadXml($xml->Result);
        }

        if (isset($xml->Fault)) {
            $this->Fault = new AuthServiceFault();
            $this->Fault->loadXml($xml->Fault);
        }
    }
}
