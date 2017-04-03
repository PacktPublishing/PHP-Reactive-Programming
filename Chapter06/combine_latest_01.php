<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;
use Rx\Subject\Subject;
use Rx\Observable;

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

$source = Observable::just(42)
    ->combineLatest([
        Observable::interval(175, $scheduler)->take(3)->startWith(null),
        Observable::interval(250, $scheduler)->take(3)->startWith(null),
        Observable::interval(100, $scheduler)->take(5)->startWith(null),
    ])
    ->subscribe(new DebugSubject());

$loop->run();
