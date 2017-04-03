<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Subject\ReplaySubject;
use Rx\Subject\Subject;

$subject = new Subject();

$observable = Observable::defer(function() use ($subject) {
        printf("Observable::defer\n");
        return $subject;
    })
    ->multicast(new ReplaySubject(1));

$observable->connect();
$subject->onNext(42);

$observable->subscribe(new DebugSubject('1'));
$observable->subscribe(new DebugSubject('2'));

$subject->onNext(24);
$subject->onCompleted();
