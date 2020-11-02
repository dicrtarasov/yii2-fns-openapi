# OpenAPI проверки чеков от ФНС для Yii2

- [Проект ФНС](https://kkt-online.nalog.ru/)
- [Технические условия использования сервиса ФНС России «Открытое API проверки чека ККТ»](https://www.nalog.ru/files/kkt/pdf/%D0%A2%D0%B5%D1%85%D0%BD%D0%B8%D1%87%D0%B5%D1%81%D0%BA%D0%B8%D0%B5%20%D1%83%D1%81%D0%BB%D0%BE%D0%B2%D0%B8%D1%8F%20%D0%B8%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D1%8F.pdf)

## Конфигурация

Для работы с API компоненту необходимо указать ключ (masterKey). Также необходимо зарегистрировать IP-адрес, 
с которого будут отправляться запросы.

```php
'components' => [
    'fnsClient' => [
        'class' => dicr\fns\openapi\FNSClient::class,
        'masterToken' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
    ]
];
```

## Использование

```php
use dicr\fns\openapi\FNSClient;
use dicr\fns\openapi\types\GetTicketInfo;
use dicr\fns\openapi\types\GetTicketResult;
use dicr\fns\openapi\types\TypeOperation;

/** @var FNSClient $fnsClient получаем клиент API */
$fnsClient = Yii::$app->get('fnsClient');

// данные чека
$ticketInfo = new GetTicketInfo([
   'Sum' => 99100,
   'Date' => '2020-10-03T15:27:00',
   'Fn' => '9280440300430432',
   'TypeOperation' => TypeOperation::INCOME,
   'FiscalDocumentId' => 29127,
   'FiscalSign' => 266252041
]);

/** @var GetTicketResult $result получаем данные по чеку */
$result = $fnsClient->getTicket($ticketInfo);

// проверяем код возвраща
if ($result->Code !== 200) {
    throw new \yii\base\Exception($result->Message);
}

// данные билета в $result->Ticket
print_r($result->Ticket);
```
