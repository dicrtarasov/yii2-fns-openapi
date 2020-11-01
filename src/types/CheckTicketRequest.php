<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 03:36:46
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use yii\base\Model;

/**
 * Запрос на проверку чека.
 */
class CheckTicketRequest extends Model
{
    /** @var ?TicketInfo Данные, необходимые для проверки ФД */
    public $CheckTicketInfo;

    /** @var ?GeoInfo Геокоординаты места запроса на проверку ФД */
    public $GeoInfo;
}
