<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/JSONDecodeOperator.php';
require_once './StreamObservable.php';

use React\Promise\Deferred;
use Rx\Observable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\Subject;
use Rx\Subject\ReplaySubject;
use Rx\React\Promise;
use React\EventLoop\LoopInterface;

class GameServerStreamEndpoint {

    /** @var StreamObservable */
    private $stream;
    /** @var Deferred */
    private $initDeffered;
    /** @var ConnectableObservable */
    private $status;

    public function __construct($stream, LoopInterface $loop) {
        $this->stream = new StreamObservable($stream, $loop);

        $this->initDeffered = new Deferred();

        $decodedMessage = $this->stream
            ->lift(function() {
                return new JSONDecodeOperator();
            });

        $unsubscribe = $decodedMessage
            ->filter(function($message) {
                return $message['type'] == 'init';
            })
            ->pluck('data')
            ->subscribeCallback(function($data) use (&$unsubscribe) {
                $this->initDeffered->resolve($data['port']);
                $unsubscribe->dispose();
            });

        $this->status = $decodedMessage
            ->filter(function($message) {
                return $message['type'] == 'status';
            })
            ->pluck('data')
            ->multicast(new ReplaySubject(1));
        $this->status->connect();
    }

    public function getStatus() {
        return $this->status;
    }

    public function onInit() {
        return $this->initDeffered->promise();
    }

    public function close() {
        return $this->stream->close();
    }

}