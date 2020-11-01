<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 03:21:26
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

/**
 * Class GetMessageResponse
 */
class GetMessageResponse
{
    /**
     * @var ?string
     * @see ProcessingStatuses
     */
    public $ProcessingStatus;

    /** @var Message */
    public $Message;
}
