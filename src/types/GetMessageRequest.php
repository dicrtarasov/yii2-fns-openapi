<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:49:53
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

/**
 * Class GetMessageRequest
 */
class GetMessageRequest extends Model
{
    /** @var ?string */
    public $MessageId;

    /** @var ?Message */
    public $Message;

    /** @var string ns URI */
    public $xmlns;

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('ns:GetMessageRequest', [
            'xmlns:ns' => $this->xmlns
        ]);

        if (! empty($this->MessageId)) {
            echo Html::xml('ns:MessageId', Html::esc($this->MessageId));
        }

        if (! empty($this->Message)) {
            echo $this->Message;
        }

        echo Html::endTag('ns:GetMessageRequest');

        return ob_get_clean();
    }
}
