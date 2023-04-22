<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Wire\AMQPTable;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class sendDelayMessage
{
  public function __construct($message = null, $delay)
  {
    $this->connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest', 'guest');
    $this->channel = $this->connection->channel();
    $this->message = $message;
    $this->delay = $delay;
  }

  public function send()
  {
    $this->channel->queue_declare('delayed_queue', false, false, false, false, false, new AMQPTable(array(
      'x-dead-letter-exchange' => 'delayed'
    )));

    $headers = new AMQPTable(array('x-delay' => $this->delay));
    $message = new AMQPMessage($this->message, array('delivery_mode' => 2));
    $message->set('application_headers', $headers);
    $this->channel->basic_publish($message, 'delayed_exchange');

    $this->channel->close();
    $this->connection->close();
  }
}

$sendDelayMessage = new sendDelayMessage('Im tried.', 1000);
$sendDelayMessage->send();
