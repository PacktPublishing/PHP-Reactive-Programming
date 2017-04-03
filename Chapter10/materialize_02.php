<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Notification\OnNextNotification;

Observable::create(function(\Rx\ObserverInterface $observer) {
        $observer->onNext(1);
        $observer->onNext(2);
        $observer->onError(new \Exception("It's broken"));
        $observer->onNext(4);
    })
    ->materialize()
    ->subscribe(new DebugSubject());
