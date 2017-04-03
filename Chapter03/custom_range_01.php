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

        return $sched->schedule(function() use ($observer) {
            for ($i = $this->min; $i <= $this->max; $i++) {
                $observer->onNext($i);
            }

            $observer->onCompleted();
        });
    }
}

//(new CustomRangeObservable(1, 5))
//    ->subscribe(new DebugSubject());

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