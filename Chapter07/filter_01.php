<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;
use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

$lastTimestamp = 0;

Observable::interval(150, $scheduler)
    ->filter(function() use (&$lastTimestamp) {
        if ($lastTimestamp + 1 <= time()) {
            $lastTimestamp = time();
            return true;
        } else {
            return false;
        }
    })
    ->subscribe(new DebugSubject());

$loop->run();
