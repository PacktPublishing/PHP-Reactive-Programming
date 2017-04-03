<?php

require_once __DIR__ . '/../vendor/autoload.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

$observer = new \Rx\Observer\CallbackObserver(function($value) {
    printf("%s\n", $value);
}, null, function() {
    print("Complete\n");
});

\Rx\Observable::fromArray($fruits)
    ->map(function($value) {
        return strlen($value);
    })
    ->subscribe($observer);