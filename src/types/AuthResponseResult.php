<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 12:25:58
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

/**
 * Class AuthResponseResult
 */
class AuthResponseResult
{
    /** @var string Токен сгенерированный для внешнего приложения */
    public $Token;

    /** @var string Y-m-d\TH:i:s\Z Дата и время истечения сгенерерированного токена */
    public $ExpireTime;
}
