<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:36:18
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\fns\openapi\FNSClient;
use dicr\helper\Html;
use SimpleXMLElement;
use Yii;
use yii\base\Model;

use function ob_get_clean;

/**
 * Class Message
 */
class Message extends Model
{
    /** @var mixed */
    public $any;

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('ns:Message');
        echo (string)$this->any;
        echo Html::endTag('ns:Message');

        return ob_get_clean();
    }

    /**
     * Создать из XML
     *
     * @param SimpleXMLElement $xml
     * @return static
     */
    public static function fromXml(SimpleXMLElement $xml) : self
    {
        $self = new static();

        // проверяем наличие AuthService
        $child = $xml->children(FNSClient::XMLNS_AUTH);
        if (isset($child->AuthResponse)) {
            $self->any = AuthResponse::fromXml($child->AuthResponse);
        } else {
            // проверяем наличие KktService
            $child = $xml->children(FNSClient::XMLNS_KKT);
            if (isset($child->CheckTicketResponse)) {
                $self->any = CheckTicketResponse::fromXml($child->CheckTicketResponse);
            } elseif (isset($child->GetTicketResponse)) {
                $self->any = GetTicketResponse::fromXml($child->GetTicketResponse);
            } else {
                Yii::warning('Неизвестный тип вложенного сообщения: ' . $xml->asXML(), __METHOD__);
                $self->any = $xml;
            }
        }

        return $self;
    }
}
