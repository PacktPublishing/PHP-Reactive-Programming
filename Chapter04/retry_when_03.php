<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once '../Chapter 02/CURLObservable.php';

use Rx\Observable;

$loop = new \React\EventLoop\StreamSelectLoop();
$scheduler = new \Rx\Scheduler\EventLoopScheduler($loop);

(new CURLObservable('https://example.com123'))
    ->retryWhen(function(Observable $errObs) use ($scheduler) {
        $i = 1;
        echo "retryWhen\n";
        $notificationObs = $errObs
            ->delay(1000, $scheduler)
            ->map(function(Exception $val) use (&$i) {
                echo "attempt: $i\n";
                $i++;
                return $val;
            })
            ->take(3);

        return $notificationObs;
    })
    ->subscribe(new DebugSubject(), $scheduler);

$loop->run();
