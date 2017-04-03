<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

const MAX_FILES = 1000;
const BUFFER_SIZE = 20;

Observable::create(function (ObserverInterface $observer) use ($loop) {
        $start = microtime(true);
        $dirIter = new \RecursiveDirectoryIterator(__DIR__ . '/../symfony_template/vendor');
        $iter = new \RecursiveIteratorIterator($dirIter);
        $filteredIter = new \RegexIterator($iter, '/.+\.php$/', RegexIterator::GET_MATCH);

        Observable::fromIterator($filteredIter)
            ->filter(function($filenames) {
                return is_readable($filenames[0]);
            })
            ->pluck(0)
            ->subscribeCallback(function($filename) use ($observer) {
                $observer->onNext($filename);
            });

        return new CallbackDisposable(function() use ($loop, $start) {
            echo "duration: " . round(microtime(true) - $start, 2) . "s\n";
            $loop->stop();
        });
    })
    ->bufferWithCount(BUFFER_SIZE)
    ->map(function($filenames) {
        return new PHPParserThread($filenames);
    })
    ->lift(function() {
        return new ThreadPoolOperator(4, PHPParserWorker::class, [__DIR__ . '/../vendor/autoload.php']);
    })
    ->flatMap(function($result) {
        return Observable::fromArray((array)$result);
    })
    ->take(MAX_FILES)
    ->filter(function($result) {
        return count($result['results']) > 0;
    })
    ->subscribeCallback(function($result) {
//        print_r($result);
    }, null, null, $scheduler);

$loop->run();