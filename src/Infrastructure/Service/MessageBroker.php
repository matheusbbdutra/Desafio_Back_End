<?php

namespace App\Infrastructure\Service;

use Bunny\Async\Client;
use Bunny\Message;
use React\EventLoop\Loop;

class MessageBroker
{
    private $client;
    private $channel;

    public function __construct()
    {
        $loop = Loop::get();

        $this->client = new Client($loop, [
            'host' => 'rabbitmq',
            'vhost' => '/',
            'user' => 'app',
            'password' => '!ChangeMe!',
            'port' => '5672',
        ]);
    }

    public function sendMessage(string $queueName, string $message, string $recipient)
    {
        $content = json_encode(['message' => $message, 'recipient' => $recipient]);
        $this->channel->publish($content, [], '', $queueName);
    }

    public function receive(): ?Message
    {
        $message = null;

        $this->client->connect()->then(function (Client $client) {
            return $client->channel();
        })->then(function ($channel) use (&$message) {
            $this->channel = $channel;
            return $channel->queueDeclare('email_queue');
        })->then(function () use (&$message) {
            $message = $this->channel->basic_get('email_queue');
            $this->client->stop();
        });

        $this->client->run();

        return $message ?: null;
    }

    public function close()
    {
        $this->client->disconnect();
    }

    public function __destruct()
    {
        $this->close();
    }

    public function acknowledge(Message $message)
    {
        $this->channel->ack($message);
    }
}