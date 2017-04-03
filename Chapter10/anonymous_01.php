<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObservableInterface;
use Rx\ObserverInterface;

Observable::range(1, 5)
    ->map(function($val) {
        return $val * 2;
    })
    ->lift(function() {
        return function(ObservableInterface $observable, ObserverInterface $observer, $scheduler) {
            var_dump(get_class($observable));
            var_dump(get_class($observer));
        };
    })
    ->subscribe(new DebugSubject());