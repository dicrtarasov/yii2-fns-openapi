<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 03:19:34
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

/**
 * Состояние обработки.
 */
interface ProcessingStatuses
{
    /** @var string */
    public const PROCESSING = 'PROCESSING';

    /** @var string */
    public const COMPLETED = 'COMPLETED';
}
