<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Scheduler\VirtualTimeScheduler;

$scheduler = new VirtualTimeScheduler(100, function($a, $b) {
    return $a - $b;
});

$scheduler->schedule(function() {
    print("1\n");
}, 300);
$scheduler->schedule(function() {
    print("2\n");
}, 0);
$scheduler->schedule(function() {
    print("3\n");
}, 150);

$scheduler->start();
