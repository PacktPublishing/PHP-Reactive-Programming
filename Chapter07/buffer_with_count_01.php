<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once 'ThrottleTimeOperator.php';

use Rx\Observable;
use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

$lastTimestamp = 0;

Observable::interval(500, $scheduler)
    ->bufferWithCount(4)
    ->subscribe(new DebugSubject());

$loop->run();
