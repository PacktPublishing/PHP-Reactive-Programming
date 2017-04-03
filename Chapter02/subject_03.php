<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'PrintObserver.php';

use Rx\Subject\Subject;
use Rx\Observable;

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

$subject = new Subject();
$subject
    ->filter(function($len) {
        return $len > 5;
    })
    ->subscribe(new PrintObserver());

Observable::fromArray($fruits)
    ->map(function($value) {
        return strlen($value);
    })
    ->subscribe($subject);

