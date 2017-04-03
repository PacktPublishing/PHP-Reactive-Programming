<?php

require_once '../vendor/autoload.php';

use React\EventLoop\StreamSelectLoop;

$loop1 = new StreamSelectLoop();
$loop1->addPeriodicTimer(1, function() {
    echo "timer 1\n";
});

$loop2 = new StreamSelectLoop();
$loop2->addTimer(2, function() {
    echo "timer 2\n";
});

$loop1->run();
$loop2->run();
