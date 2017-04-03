<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;
use Rx\Observable\IntervalObservable;

$process = new Process('php wrapped_curl.php curl https://www.reddit.com/r/php/new.json');
$process->start();

$loop = new React\EventLoop\StreamSelectLoop();
$scheduler = new Rx\Scheduler\EventLoopScheduler($loop);

(new IntervalObservable(1000, $scheduler))
    ->takeWhile(function($ticks) use ($process) {
        return $process->isRunning();
    })
    ->subscribeCallback(function($ticks) {
        printf("${ticks}\n");
    }, function() {}, function() use ($process) {
        echo $process->getOutput();
    });

$loop->run();