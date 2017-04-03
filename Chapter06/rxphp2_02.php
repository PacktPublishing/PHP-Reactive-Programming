<?php

require __DIR__ . "/vendor/autoload.php";

use Rx\Observable;

Observable::interval(1000)
    ->take(3)
    ->subscribe(function($value) {
        echo "First: $value\n";
    });

Observable::interval(1000)
    ->take(3)
    ->subscribe(function($value) {
        echo "Second: $value\n";
    });


