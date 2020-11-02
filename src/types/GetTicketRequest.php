<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:22:51
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\fns\openapi\FNSClient;
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
            'xmlns:tns' => FNSClient::XMLNS_KKT
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
