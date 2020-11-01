<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 03:20:31
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use yii\base\Model;

/**
 * Class GetMessageRequest
 */
class GetMessageRequest extends Model
{
    /** @var ?string */
    public $MessageId;

    /** @var ?Message */
    public $Message;
}
