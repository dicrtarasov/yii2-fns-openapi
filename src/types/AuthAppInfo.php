<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 23:36:15
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;

/**
 * Class AuthAppInfo
 */
class AuthAppInfo extends Model
{
    /** @var string мастер-токен, выданный ФНС */
    public $MasterToken;

    /**
     * Конвертирует в XML.
     *
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('tns:AuthAppInfo');
        echo Html::xml('tns:MasterToken', Html::esc($this->MasterToken));
        echo Html::endTag('tns:AuthAppInfo');

        return ob_get_clean();
    }
}
