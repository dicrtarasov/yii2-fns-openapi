<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 05:16:43
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;

/**
 * Запрос на проверку чека.
 */
class GetTicketRequest extends Model
{
    /** @var ?GetTicketInfo Данные, необходимые для проверки ФД */
    public $GetTicketInfo;

    /** @var ?GeoInfo Геокоординаты места запроса на проверку ФД */
    public $GeoInfo;

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('tns:GetTicketRequest', [
            'xmlns:tns' => 'urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0'
        ]);

        if (! empty($this->GetTicketInfo)) {
            echo (string)$this->GetTicketInfo;
        }

        if (! empty($this->GeoInfo)) {
            echo (string)$this->GeoInfo;
        }

        echo Html::endTag('tns:GetTicketRequest');

        return ob_get_clean();
    }
}
