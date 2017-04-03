<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/ThreadPoolOperator.php';
require_once __DIR__ . '/PHPParserOperator.php';
require_once __DIR__ . '/PHPParserThread.php';
require_once __DIR__ . '/PHPParserWorker.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Scheduler\EventLoopScheduler;
use Rx\Disposable\CallbackDisposable;
use React\EventLoop\StreamSelectLoop;

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

Observable::create(function(ObserverInterface $observer) {
        $observer->onNext('_test_source_code.php');
    })
    ->map(function($filename) {
        return new PHPParserThread($filename);
    })
    ->lift(function() {
        $args = [__DIR__ . '/../vendor/autoload.php'];
        return new ThreadPoolOperator(2, PHPParserWorker::class, $args);
    })
    ->flatMap(function($result) {
        return Observable::fromArray((array)$result);
    })
    ->take(1)
    ->subscribeCallback(function($result) {
        print_r($result);
    }, null, null, $scheduler);

$loop->run();
