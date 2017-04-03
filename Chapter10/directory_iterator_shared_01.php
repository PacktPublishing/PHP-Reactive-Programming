<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorSharedObservable.php';

$source = new DirectoryIteratorSharedObservable('.', '/.+\.php$/');

$source->subscribe(new DebugSubject('1'));
$source->subscribe(new DebugSubject('2'));
$source->subscribe(new DebugSubject('3'));

$source->connect();