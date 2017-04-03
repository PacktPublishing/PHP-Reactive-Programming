<?php

require_once '../vendor/autoload.php';

use React\EventLoop\StreamSelectLoop;

$stdin = fopen('php://stdin', 'r');

$loop = new StreamSelectLoop();
$loop->addReadStream($stdin, function($stream) {
    $str = trim(fgets($stream));
    echo strrev($str) . "\n";
});

$loop->run();