<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';

$loop = new React\EventLoop\StreamSelectLoop();
$scheduler = new Rx\Scheduler\EventLoopScheduler($loop);

Rx\Observable::interval(1000, $scheduler)
    ->subscribe(new DebugSubject());
//    ->take(3)

$loop->run();