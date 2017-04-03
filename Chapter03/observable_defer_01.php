<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\ObserverInterface;

$source = Observable::range(0, rand(1, 10));

$source->subscribe(new DebugSubject());
$source->subscribe(new DebugSubject());