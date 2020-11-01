<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 05:20:15
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;

/**
 * Информация о местоположении пользователя во время выполнения запроса.
 */
class GeoInfo extends Model
{
    /** @var float Широта */
    public $Latitude;

    /** @var float Долгота */
    public $Longitude;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            [['Latitude', 'Longitude'], 'required'],
            [['Latitude', 'Longitude'], 'number', 'min' => 0]
        ];
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('tns:GeoInfo');
        echo Html::xml('tns:Latitude', (float)$this->Latitude);
        echo Html::xml('tns:Longitude', (float)$this->Longitude);
        echo Html::endTag('tns:GeoInfo');

        return ob_get_clean();
    }
}
