<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;
use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

$source = Observable::interval(1000, $scheduler)
    ->take(3)
    ->map(function($value) use ($scheduler) {
        return Observable::interval(600, $scheduler)
            ->take(3)
            ->map(function($counter) use ($value) {
                return sprintf('#%d: %d', $value, $counter);
            });
    });

$source->switchLatest()
    ->subscribe(new DebugSubject());

$loop->run();
