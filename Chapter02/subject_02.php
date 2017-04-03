<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'PrintObserver.php';

use Rx\Subject\Subject;
use Rx\Observable;

$subject = new Subject();
$subject->subscribe(new PrintObserver());

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

Observable::fromArray($fruits)
    ->map(function($value) {
        return strlen($value);
    })
    ->filter(function($len) {
        return $len > 5;
    })
    ->subscribe($subject);
