<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Subject\Subject;

$subject = new Subject();

$subject->subscribe(new DebugSubject('1'));
$subject->onNext(1);
$subject->onNext(2);
$subject->onNext(3);
$subject->onCompleted();

$subject->subscribe(new DebugSubject('2'));
$subject->onNext(4);
$subject->onCompleted();
