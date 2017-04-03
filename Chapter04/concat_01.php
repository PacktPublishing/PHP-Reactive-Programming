<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

$loop = new \React\EventLoop\StreamSelectLoop();
$scheduler = new \Rx\Scheduler\EventLoopScheduler($loop);


$concat = Observable::interval(100)
    ->map(function($value) {
        return 'C' . $value;
    })
    ->take(3);

$source = Observable::interval(300)
    ->map(function($value) {
        return 'S' . $value;
    })
    ->take(3)
    ->concat($concat)
    ->subscribe(new DebugSubject(), $scheduler);

$loop->run();