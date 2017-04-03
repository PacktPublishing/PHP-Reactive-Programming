<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

$loop      = \React\EventLoop\Factory::create();
$scheduler = new \Rx\Scheduler\EventLoopScheduler($loop);

$count = 0;

Rx\Observable::interval(1000, $scheduler)
    ->map(function () use (&$count) {
        if (++$count % 2 == 0) {
            throw new \Exception('$val % 2');
        } else {
            return $count;
        }
    })
//    ->doOnError(function() {
//        echo "doOnError triggered\n";
//    })
    ->doOnEach(new DebugSubject())
    ->retry(3)
    ->subscribe(new DebugSubject());

$loop->run();