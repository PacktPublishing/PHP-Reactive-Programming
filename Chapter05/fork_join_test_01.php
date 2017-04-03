<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'ForkJoinObservable.php';

use Rx\Observable;

(new ForkJoinObservable([
    Observable::fromArray([1, 2, 3, 4]),
    Observable::fromArray([7, 6, 5]),
    Observable::fromArray(['a', 'b', 'c']),
]))->subscribeCallback(function($values) {
    print_r($values);
});