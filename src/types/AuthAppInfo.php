<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 10:56:53
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

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
        return Html::xml('tns:AuthAppInfo',
            Html::xml('tns:MasterToken', Html::esc($this->MasterToken))
        );
    }
}
