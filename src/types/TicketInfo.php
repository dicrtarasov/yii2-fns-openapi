<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 31.10.20 19:51:01
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use yii\base\Model;

/**
 * Параметры чека для запроса информации.
 */
class TicketInfo extends Model
{
    /** @var int приход */
    public const TYPE_OP_INCOME = 1;

    /** @var int возврат прихода */
    public const TYPE_OP_INCOME_RETURN = 2;

    /** @var int расход */
    public const TYPE_OP_EXPENSE = 3;

    /** @var int возврат расхода */
    public const TYPE_OP_EXPENSE_RETURN = 4;

    /** @var int Сумма чека в копейках */
    public $Sum;

    /** @var string|int Дата и время операции в формате yyyy-MM-dd'T'HH':'mm':'ss или UnixTime */
    public $Date;

    /** @var string Номер ФН */
    public $Fn;

    /** @var int Тип операции (TYPE_OP_*) */
    public $TypeOperation;

    /** @var int Порядковый номер ФД */
    public $FiscalDocumentId;

    /** @var int Фискальный признак документа */
    public $FiscalSign;

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['Sum', 'required'],
            ['Sum', 'integer', 'min' => 1, 'max' => 2 ** 48 - 2],
            ['Sum', 'filter', 'filter' => 'intval'],

            ['Date', 'required'],
            ['Date', 'date', 'format' => 'php:Y-m-d\TH:i:s'],

            // строка с цифрами (может начинаться с 0)
            ['Fn', 'trim'],
            ['Fn', 'required'],
            ['Fn', 'integer', 'min' => 1],

            ['TypeOperation', 'required'],
            ['TypeOperation', 'integer', 'min' => 1, 'max' => 4],
            ['TypeOperation', 'filter', 'filter' => 'intval'],

            [['FiscalDocumentId', 'FiscalSign'], 'required'],
            [['FiscalDocumentId', 'FiscalSign'], 'integer', 'min' => 1, 'max' => 2 ** 32 - 1],
            [['FiscalDocumentId', 'FiscalSign'], 'filter', 'filter' => 'intval']
        ];
    }
}
