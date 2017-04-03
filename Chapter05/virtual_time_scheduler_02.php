<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Scheduler\VirtualTimeScheduler;
use Rx\Observable;

$scheduler = new VirtualTimeScheduler(0, function($a, $b) {
    return $a - $b;
});

$obs = new Rx\Observer\CallbackObserver(function($val) {
    print("$val\n");
});

$observable = Observable::fromArray([1,2,3,4]);
$observable->subscribe($obs, $scheduler);

$scheduler->start();
