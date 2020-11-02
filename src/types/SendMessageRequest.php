<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 02.11.20 03:20:26
 */

declare(strict_types = 1);
namespace dicr\fns\openapi\types;

use dicr\fns\openapi\FNSClient;
use dicr\helper\Html;
use yii\base\Model;

use function ob_get_clean;

/**
 * Асинхронный запрос на отправку сообщения.
 */
class SendMessageRequest extends Model
{
    /** @var Message */
    public $Message;

    /**
     * @return string
     */
    public function __toString() : string
    {
        ob_start();
        echo Html::beginTag('ns:SendMessageRequest', [
            'xmlns:ns' => FNSClient::XMLNS_ASYNC,
        ]);

        echo $this->Message;
        echo Html::endTag('ns:SendMessageRequest');

        return ob_get_clean();
    }
}
