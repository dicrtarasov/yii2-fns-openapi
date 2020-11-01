<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 05:26:01
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

/**
 * Типы операций.
 */
interface TypeOperation
{
    /** @var int приход */
    public const INCOME = 1;

    /** @var int возврат прихода */
    public const INCOME_RETURN = 2;

    /** @var int расход */
    public const EXPENSE = 3;

    /** @var int возврат расхода */
    public const EXPENSE_RETURN = 4;
}
