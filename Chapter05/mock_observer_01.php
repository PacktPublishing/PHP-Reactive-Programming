<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Testing\MockObserver;
use Rx\Scheduler\VirtualTimeScheduler;
use Rx\Testing\HotObservable;
use Rx\Testing\Recorded;
use Rx\Notification\OnNextNotification;
use Rx\Notification\OnCompletedNotification;

$scheduler = new VirtualTimeScheduler(0, function($a, $b) {
    return $a - $b;
});

$observer = new MockObserver($scheduler);

(new HotObservable($scheduler, [
    new Recorded(100, new OnNextNotification(3)),
    new Recorded(150, new OnNextNotification(1)),
    new Recorded(80, new OnNextNotification(2)),
    new Recorded(140, new OnCompletedNotification()),
]))->subscribe($observer);

$scheduler->start();

foreach ($observer->getMessages() as $message) {
    /** @var Recorded $message */
    printf("%s: %s\n", $message->getTime(), $message->getValue());
}
