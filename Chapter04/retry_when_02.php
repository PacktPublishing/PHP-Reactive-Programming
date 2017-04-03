<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once '../Chapter 02/CURLObservable.php';

use Rx\Observable;

$loop = new \React\EventLoop\StreamSelectLoop();
$scheduler = new \Rx\Scheduler\EventLoopScheduler($loop);

(new CURLObservable('https://example.com123'))
    ->retryWhen(function(Observable $errObs) use ($scheduler) {
        echo "retryWhen\n";
        $i = 1;
        $notificationObs = $errObs
            ->delay(1000, $scheduler)
            ->map(function(Exception $val) use (&$i) {
                echo "attempt: $i\n";
                if ($i == 3) {
                    throw $val;
                }
                $i++;
                return $val;
            });

        return $notificationObs;
    })
    ->subscribe(new DebugSubject(), $scheduler);

$loop->run();
