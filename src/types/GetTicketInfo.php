<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 01.11.20 05:25:28
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;
use function ob_start;

/**
 * Параметры чека.
 */
class GetTicketInfo extends Model
{
    /** @var int Сумма чека в копейках */
    public $Sum;

    /** @var string|int Дата и время операции в формате yyyy-MM-dd'T'HH':'mm':'ss или UnixTime */
    public $Date;

    /** @var string Номер ФН */
    public $Fn;

    /**
     * @var int Тип операции
     * @see TypeOperation
     */
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

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('tns:GetTicketInfo');
        echo Html::xml('tns:Sum', (int)$this->Sum);
        echo Html::xml('tns:Date', Html::esc($this->Date));
        echo Html::xml('tns:Fn', Html::esc($this->Fn));
        echo Html::xml('tns:TypeOperation', (int)$this->TypeOperation);
        echo Html::xml('tns:FiscalDocumentId', (int)$this->FiscalDocumentId);
        echo Html::xml('tns:FiscalSign', (int)$this->FiscalSign);
        echo Html::endTag('tns:GetTicketInfo');

        return ob_get_clean();
    }
}
