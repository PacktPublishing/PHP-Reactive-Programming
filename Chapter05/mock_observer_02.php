<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Testing\TestScheduler;
use Rx\Testing\HotObservable;
use Rx\Testing\Recorded;
use Rx\Notification\OnNextNotification;
use Rx\Notification\OnCompletedNotification;

$scheduler = new TestScheduler();

$observer = $scheduler->startWithCreate(function() use ($scheduler) {
    return new HotObservable($scheduler, [
        new Recorded(200, new OnNextNotification(3)),
        new Recorded(250, new OnNextNotification(1)),
        new Recorded(180, new OnNextNotification(2)),
        new Recorded(240, new OnCompletedNotification()),
        new Recorded(1200, new OnNextNotification(4)),
    ]);
});

$expected = [
    new Recorded(200, new OnNextNotification(3)),
    new Recorded(240, new OnCompletedNotification()),
    new Recorded(250, new OnNextNotification(1)),
];

$actual = $observer->getMessages();
printf("Count match: %d\n", count($actual) == count($expected));
foreach ($actual as $i => $message) {
    /** @var Recorded $message */
    printf("%s: %d\n", $message->getTime(), $message->equals($expected[$i]));
}
