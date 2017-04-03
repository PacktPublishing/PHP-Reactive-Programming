<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Scheduler\EventLoopScheduler;
use React\EventLoop\StreamSelectLoop;

class CustomRangeObservable extends Observable
{

    private $min;
    private $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function subscribe(ObserverInterface $observer, $sched = null)
    {
        if (null === $sched) {
            $sched = new \Rx\Scheduler\ImmediateScheduler();
        }

        $i = $this->min;

        return $sched->scheduleRecursive(function($reschedule) use ($observer, &$i) {
            if ($i <= $this->max) {
                $observer->onNext($i);
                $i++;
                $reschedule();
            } else {
                $observer->onCompleted();
            }
        });
    }
}


$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

$disposable = (new CustomRangeObservable(1, 5))
    ->subscribeCallback(function($val) use (&$disposable) {
        echo "$val\n";
        if ($val == 3) {
            $disposable->dispose();
        }
    }, null, null, $scheduler);

$scheduler->start();