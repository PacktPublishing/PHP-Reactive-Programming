<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once './ProcessObservable.php';

use Rx\Observer\CallbackObserver;

$loop = new React\EventLoop\StreamSelectLoop();
$scheduler = new Rx\Scheduler\EventLoopScheduler($loop);

$pid = tempnam(sys_get_temp_dir(), 'pid_proc1');

$observable = new ProcessObservable('/usr/local/bin/php ../Chapter\ 02/sleep.php proc1 3', $pid);
$disposable = $observable->subscribe(new CallbackObserver(function($val) {
//    print($val);
}), $scheduler);

$loop->run();

$disposable->dispose();
