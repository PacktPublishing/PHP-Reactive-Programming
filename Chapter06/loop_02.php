<?php

require_once '../vendor/autoload.php';

use React\EventLoop\StreamSelectLoop;
use Rx\Observable;
use Rx\Scheduler\EventLoopScheduler;

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

Observable::interval(2000, $scheduler)
    ->subscribeCallback(function($counter) {
        printf("periodic timer %d\n", $counter);
    });

$stdin = fopen('php://stdin', 'r');
$loop->addReadStream($stdin, function($stream) {
    $str = trim(fgets($stream));
    echo strrev($str) . "\n";
});

$loop->run();