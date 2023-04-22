<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class receiveDelayMessage
{
  public function __construct($message = null)
  {
    $this->connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest', 'guest');
    $this->channel = $this->connection->channel();
    $this->message = $message;
  }

  public function receive()
  {
    $this->channel->queue_declare('delayed_queue', false, false, false, false, false, new AMQPTable(array(
      'x-dead-letter-exchange' => 'delayed'
    )));

    echo '[*] Waiting for messages.';

    $callback = function ($msg) {
      echo '[*] Received : ' . $msg->body . "\n";
    };

    $this->channel->basic_consume('delayed_queue', '', false, true, false, false, $callback);

    while ($this->channel->is_consuming()) {
      $this->channel->wait();
    }

    $this->channel->close();
    $this->connection->close();
  }
}

$receiveDelayMessage = new receiveDelayMessage();
$receiveDelayMessage->receive();
