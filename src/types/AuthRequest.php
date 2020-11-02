<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:21:43
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\fns\openapi\FNSClient;
use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;

/**
 * Class AuthRequest
 */
class AuthRequest extends Model
{
    /** @var $AuthAppInfo */
    public $AuthAppInfo;

    /**
     * Конвертирование в XML.
     *
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('tns:AuthRequest', [
            'xmlns:tns' => FNSClient::XMLNS_AUTH
        ]);

        echo (string)$this->AuthAppInfo;
        echo Html::endTag('tns:AuthRequest');

        return ob_get_clean();
    }
}
