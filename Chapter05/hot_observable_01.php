<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Scheduler\VirtualTimeScheduler;
use Rx\Testing\HotObservable;
use Rx\Testing\Recorded;
use Rx\Notification\OnNextNotification;

$comparer = function($a, $b) {
    return $a - $b;
};

$scheduler = new VirtualTimeScheduler(0, $comparer);

$observable = new HotObservable($scheduler, [
    new Recorded(100, new OnNextNotification(3)),
    new Recorded(150, new OnNextNotification(1)),
    new Recorded(80, new OnNextNotification(2)),
]);
$observable->subscribeCallback(function($val) {
    print("$val\n");
});

$scheduler->start();