<?php

require_once __DIR__ . '/../vendor/autoload.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

$observer = new \Rx\Observer\CallbackObserver(function($value) {
    printf("%s\n", $value);
}, function() {
    print("onError\n");
}, function() {
    print("onComplete\n");
});


\Rx\Observable::fromArray($fruits);
