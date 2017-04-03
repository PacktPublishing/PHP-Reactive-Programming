<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObservableInterface;
use Rx\ObserverInterface;
use Rx\Observer\CallbackObserver;

Observable::range(1, 5)
    ->map(function($val) {
        return $val * 2;
    })
    ->lift(function() {
        return function(ObservableInterface $observable, ObserverInterface $observer, $scheduler) {
            $prevValue = 0;
            $onNext = function($value) use ($observer, &$prevValue) {
                $observer->onNext($value * $prevValue);
                $prevValue = $value;
            };

            $innerObs = new CallbackObserver(
                $onNext,
                [$observer, 'onError'],
                [$observer, 'onCompleted']
            );

            return $observable->subscribe($innerObs);
        };
    })
    ->subscribe(new DebugSubject());