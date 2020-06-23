## PushAllSender
----

Client for send push-notifications on API https://pushall.ru

PushAll API Documentation: https://pushall.ru/blog/api

----

### Examples

#### Simple

```php
use ArtARTs36\PushAllSender\Senders\PushAllSender;
use ArtARTs36\PushAllSender\Push;

$sender = new PushAllSender(123456789, 'apiKey');
$push = new Push('Message #1', 'Hello');

$sender->push($push);
```

#### Connect in Laravel

1*. Set variables in .env:
```bash
PUSHALL_API_KEY='your key'
PUSHALL_CHANNEL_ID='your channel id'
```

2*. Binding in bootstrap/app.php

```php
$app->singleton(
    \ArtARTs36\PushAllSender\Interfaces\PusherInterface::class,
    function () {
        return new ArtARTs36\PushAllSender\Senders\PushAllSender(env('PUSHALL_CHANNEL_ID'), env('PUSHALL_API_KEY'));
    }
);
```
