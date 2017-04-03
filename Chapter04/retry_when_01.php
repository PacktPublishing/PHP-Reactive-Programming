<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once '../Chapter 02/CURLObservable.php';

use Rx\Observable;

$loop = new \React\EventLoop\StreamSelectLoop();
$scheduler = new \Rx\Scheduler\EventLoopScheduler($loop);

(new CURLObservable('https://example.com123'))
    ->retryWhen(function(Observable $errObs) use ($scheduler) {
        $notificationObs = $errObs
            ->delay(1000, $scheduler)
            ->map(function() {
                echo "onNext\n";
                return true;
            });
        return $notificationObs;
    })
    ->subscribe(new DebugSubject(), $scheduler);

$loop->run();
