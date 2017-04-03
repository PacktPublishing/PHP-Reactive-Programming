<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];
$observer = Rx\Observable::fromArray($fruits)
    ->subscribe(new DebugSubject());


