<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 31.10.20 18:55:21
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use yii\base\Model;

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
}
