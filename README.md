# How to Consume RabbitMQ using php-amqplib.

1. clone this repository.
```
git clone https://github.com/deltamas-project/rabbitmq-for-php
```

2. Install the library.
```
composer update
```

try run the script.

## Direct Message

```
cd direct_message
php send.php
php receive.php
```

## Delay Message

```
cd delay_message
php send.php
php receive.php
```

https://github.com/php-amqplib/php-amqplib/tree/master/demo