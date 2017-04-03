<?php

require __DIR__ . "/vendor/autoload.php";

use Rx\Observable;

Observable::interval(1000)
    ->take(5)
    ->flatMap(function($i) {
        return \Rx\Observable::of($i + 1);
    })
    ->subscribe(function($value) {
        echo "$value\n";
    });
