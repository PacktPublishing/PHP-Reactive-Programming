<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Observable\ConnectableObservable;
use Rx\Observable\MulticastObservable;
use Rx\Subject\Subject;

$source = Observable::defer(function() {
    printf("Observable::defer\n");
    return Observable::range(1, 3);
});
//$subject = new Subject();
//$source = Observable::range(1, 3);

$observable = new MulticastObservable($source, function() {
    return new Subject();
}, function (ConnectableObservable $connectable) {
    return $connectable->startWith('start');
});

$observable->subscribe(new DebugSubject('1'));
$observable->subscribe(new DebugSubject('2'));
