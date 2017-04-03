<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Subject\Subject;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {

    /** @var ConnectionInterface[] */
    private $connections;
    /** @var string[] */
    private $history = [];
    /** @var Subject */
    private $subject;

    public function __construct() {
        $this->subject = new Subject();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->connections[] = $conn;
        foreach (array_slice($this->history, -5, 5) as $msg) {
            $conn->send($msg);
        }
        $this->subject->onNext(null);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $this->history[] = $msg;
        foreach ($this->connections as $conn) {
            if ($from !== $conn) {
                $conn->send($msg);
            }
        }

        $this->subject->onNext(null);
    }

    public function onClose(ConnectionInterface $conn) {
        foreach ($this->connections as $index => $client) {
            if ($conn !== $client) {
                unset($this->connections[$index]);
            }
        }
        $this->subject->onNext(null);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->onClose($conn);
    }

    public function getObservable() {
        return $this->subject
            ->map(function() {
                return sprintf('clients: %d, messages: %d',
                    $this->getClientsCount(),
                    $this->getChatHistory()
                );
            })
            ->asObservable();
    }

    private function getClientsCount() {
        return count($this->connections);
    }

    private function getChatHistory() {
        return count($this->history);
    }
}