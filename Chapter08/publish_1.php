<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Subject\Subject;

$observable = Observable::defer(function() {
        printf("Observable::create\n");
        return Observable::range(1, 3);
    })
    ->publish();

$observable->subscribe(new DebugSubject('1'));
$observable->subscribe(new DebugSubject('2'));
$observable->connect();
