<?php

require_once '../vendor/autoload.php';

use React\EventLoop\StreamSelectLoop;

$loop = new StreamSelectLoop();
$loop->addTimer(1.5, function() {
    echo "timer 1\n";
});

$counter = 0;
$loop->addPeriodicTimer(1, function () use (&$counter, $loop) {
    printf("periodic timer %d\n", ++$counter);
    if ($counter == 5) {
        $loop->stop();
    }
});

$loop->run();
