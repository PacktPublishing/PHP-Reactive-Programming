<?php

require_once __DIR__ . '/../vendor/autoload.php';

$scheduler = new \Rx\Scheduler\ImmediateScheduler();
$scheduler->schedule(function() {
    print("first\n");
});
$scheduler->schedule(function() {
    print("second\n");
});
