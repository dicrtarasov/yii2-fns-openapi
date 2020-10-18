<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 09:43:16
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use yii\base\BaseObject;

/**
 * Ошибка сервиса сообщений.
 */
class AuthServiceFault extends BaseObject
{
    /** @var string Сообщение об ошибке */
    public $Message;
}
