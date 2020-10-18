<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 10:57:17
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

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
        return Html::xml('tns:AuthRequest', (string)$this->AuthAppInfo, [
            'xmlns:tns' => 'urn://x-artefacts-gnivc-ru/ais3/kkt/AuthService/types/1.0'
        ]);
    }
}
