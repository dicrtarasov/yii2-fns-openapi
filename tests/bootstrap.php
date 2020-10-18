<?php

/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 18.10.20 08:45:27
 */
declare(strict_types = 1);

/** среда разработки */

use yii\log\Dispatcher;

defined('YII_ENV') || define('YII_ENV', 'dev');

/** режим отладки */
defined('YII_DEBUG') || define('YII_DEBUG', true);

require_once(dirname(__DIR__) . '/vendor/autoload.php');
require_once(dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php');

/** @noinspection PhpUnhandledExceptionInspection */
new yii\console\Application([
    'id' => 'test',
    'basePath' => dirname(__DIR__),
    'components' => [
        'urlManager' => [
            'hostInfo' => 'https://localhost'
        ],
        'log' => [
            'class' => Dispatcher::class,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning', 'info', 'trace']
                ]
            ]
        ],
        'fnsClient' => [
            'class' => dicr\fns\openapi\FNSClient::class,
            'masterToken' => '8hUKp79w1d7nroMxv3Og0gmYjydfnrljK86Ov6UF3E1UC1cfBiwamIuLZiv7UwmWfPf9BkpryypKPSlmGkv485EONSYKvpQ7UUwX43c6kfzfTcu1BoPUIPn6yMTUliP6'
        ]
    ],
    'bootstrap' => ['log']
]);
