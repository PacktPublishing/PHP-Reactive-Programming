<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable;
use Rx\ObserverInterface;

Observable::create(function(ObserverInterface $obs) {
        $obs->onNext(1);
        $obs->onNext(2);
        $obs->onError(new \Exception("it's broken"));
    })
    ->doOnError(function(\Exception $value) {
        echo 'doOnError: ' . $value->getMessage() . "\n";
    })
    ->subscribeCallback(function($value) {
        echo "$value\n";
    }, function() {});
