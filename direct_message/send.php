<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class sendDirectMessage
{
  public function __construct($message = null)
  {
    $this->connection = new AMQPStreamConnection('110.232.87.251', 5672, 'admin', 'admin');
    $this->channel = $this->connection->channel();
    $this->message = $message;
  }

  public function send()
  {
    $this->channel->queue_declare('mail', true, false, false, false);

    $message = new AMQPMessage($this->message, array('delivery_mode' => 2));
    $this->channel->basic_publish($message, '', 'mail');

    $this->channel->close();
    $this->connection->close();
  }
}

$sendDirectMessage = new sendDirectMessage(json_encode([
  "to" => "fadjrir.co.id@gmail.com",
  "subject" => "Hello, World!",
  "body" => "Aku ganteng banget <3",
]));
$sendDirectMessage->send();
