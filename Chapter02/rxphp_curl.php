<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';
require_once __DIR__ . '/JSONDecodeOperator.php';
require_once __DIR__ . '/CURLObservable.php';

use Rx\Observable;
use Rx\ObserverInterface as ObserverI;
use Rx\Scheduler\ImmediateScheduler;
use Rx\Disposable\CompositeDisposable;


// https://api.reddit.com/r/nosleep/hot?limit=5
$observable = new CurlObservable('https://api.stackexchange.com/2.2/questions?order=desc&sort=creation&tagged=functional-programming&site=stackoverflow');
//    ->distinct()
$observable
    ->filter(function($value) {
        return is_string($value);
    })
    ->lift(function() {
        return new JSONDecodeOperator();
    })
    ->subscribe(new DebugSubject(null, 128));

