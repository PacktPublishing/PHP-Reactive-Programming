<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'PrintObserver.php';

use Rx\Subject\Subject;

$subject = new Subject();
$subject
    ->map(function($value) {
        return strlen($value);
    })
    ->filter(function($len) {
        return $len > 5;
    })
    ->subscribe(new PrintObserver());

$subject->onNext('apple');
$subject->onNext('banana');
$subject->onNext('orange');
$subject->onNext('raspberry');
