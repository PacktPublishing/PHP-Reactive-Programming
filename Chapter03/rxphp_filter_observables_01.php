<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable\RangeObservable;
use Rx\Observable\ConnectableObservable;

$connObs = new ConnectableObservable(new RangeObservable(0, 6));
$filteredObs = $connObs
    ->map(function($val) {
        return $val ** 2;
    })
    ->filter(function($val) {
        return $val % 2;
    });

$disposable1 = $filteredObs->subscribeCallback(function($val) {
    echo "S1: ${val}\n";
});
$disposable2 = $filteredObs->subscribeCallback(function($val) {
    echo "S2: ${val}\n";
});

$connObs->connect();