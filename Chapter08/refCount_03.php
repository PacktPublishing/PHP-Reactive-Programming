<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Observable\ConnectableObservable;

$source = Rx\Observable::create(function(ObserverInterface $observer) {
    $observer->onNext(1);
    $observer->onNext(2);
    $observer->onNext(3);
});
$conn = (new Rx\Observable\ConnectableObservable($source))
    ->refCount();

$sub = $conn->subscribe(new DebugSubject('1'));
$sub->dispose();
$conn->subscribe(new DebugSubject('2'));

