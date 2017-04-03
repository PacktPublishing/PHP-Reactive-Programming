<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';
require_once __DIR__ . '/PrintObserver.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

$subject = new DebugSubject(1);
$subject
    ->map(function($item) {
        return strlen($item);
    })
    ->subscribe(new DebugSubject(2));

$observable = Rx\Observable::fromArray($fruits);
$observable->subscribe($subject);


