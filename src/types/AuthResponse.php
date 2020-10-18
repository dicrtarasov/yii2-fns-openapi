<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 09:44:13
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use yii\base\Model;

/**
 * Class AuthResponse
 */
class AuthResponse extends Model
{
    /** @var ?AuthResponseResult */
    public $Result;

    /** @var ?AuthServiceFault */
    public $Fault;
}
