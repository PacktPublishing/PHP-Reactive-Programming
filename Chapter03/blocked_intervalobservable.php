<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable\IntervalObservable;

function getTime() {
    $t = microtime(true);
    $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
    return date('H:i:s') . '.' . $micro;
}

$loop = new React\EventLoop\StreamSelectLoop();
$scheduler = new Rx\Scheduler\EventLoopScheduler($loop);
$observable = new IntervalObservable(1000, $scheduler);

$observable->map(function($tick) {
    printf("%s Map: %d\n", getTime(), $tick);
    usleep(1250 * 1000);
    return $tick;
})->subscribeCallback(function($tick) {
    printf("%s Observer: %d\n", getTime(), $tick);
});

$loop->run();