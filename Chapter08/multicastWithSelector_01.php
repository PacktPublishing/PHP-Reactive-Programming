<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\ReplaySubject;
use Rx\Subject\Subject;

$subject = new Subject();

//$source = Observable::defer(function() {
////        printf("Observable::defer\n");
//        return Observable::range(1, 3);
//    })
$source = Observable::range(1, 3)
    ->multicastWithSelector(function() use ($subject) {
        return new Subject();
    }, function(ConnectableObservable $connectable) {
//        printf("multicast\n");
        return $connectable->concat(Observable::just('concat'));
    });

$source->subscribe(new DebugSubject());
$source->subscribe(new DebugSubject());
