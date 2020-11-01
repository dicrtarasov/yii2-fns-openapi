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
 * Асинхронный запрос на отправку сообщения.
 */
class SendMessageRequest extends Model
{
    /** @var Message */
    public $Message;
}
