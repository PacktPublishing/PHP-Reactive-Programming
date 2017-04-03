<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

$loop = new \React\EventLoop\StreamSelectLoop();
$scheduler = new \Rx\Scheduler\EventLoopScheduler($loop);


$merge = Observable::interval(100)
    ->map(function($value) {
        return 'M' . $value;
    })
    ->take(3);

$source = Observable::interval(300)
    ->map(function($value) {
        return 'S' . $value;
    })
    ->take(3)
    ->merge($merge)
    ->subscribe(new DebugSubject(), $scheduler);

$loop->run();
