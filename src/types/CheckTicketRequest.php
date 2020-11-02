<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:22:17
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\fns\openapi\FNSClient;
use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;
use function ob_start;

/**
 * Запрос на проверку чека.
 */
class CheckTicketRequest extends Model
{
    /** @var ?CheckTicketInfo Данные, необходимые для проверки ФД */
    public $CheckTicketInfo;

    /** @var ?GeoInfo Геокоординаты места запроса на проверку ФД */
    public $GeoInfo;

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('tns:CheckTicketRequest', [
            'xmlns:tns' => FNSClient::XMLNS_KKT
        ]);

        if (! empty($this->CheckTicketInfo)) {
            echo (string)$this->CheckTicketInfo;
        }

        if (! empty($this->GeoInfo)) {
            echo (string)$this->GeoInfo;
        }

        echo Html::endTag('tns:CheckTicketRequest');

        return ob_get_clean();
    }
}
