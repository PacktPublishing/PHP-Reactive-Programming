<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Operator\OperatorInterface;
use Rx\ObservableInterface;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;
use Rx\Observer\CallbackObserver;
use Rx\Observable;
use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;


class ThrottleTimeOperator implements OperatorInterface
{

    private $duration;
    private $lastTimestamp = 0;

    public function __construct($duration)
    {
        $this->duration = $duration;
    }

    public function __invoke(ObservableInterface $observable, ObserverInterface $observer, SchedulerInterface $scheduler = null)
    {
        /** @var Observable $observable */
        $disposable = $observable->filter(function() use ($observer) {
            $now = microtime(true) * 1000;
            if ($this->lastTimestamp + $this->duration <= $now) {
                $this->lastTimestamp = $now;
                return true;
            } else {
                return false;
            }
        })->subscribe($observer);

        return $disposable;
    }
}