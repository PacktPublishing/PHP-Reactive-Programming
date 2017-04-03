<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

$iterator = function() use ($fruits) {
    foreach ($fruits as $fruit) {
        yield $fruit;
    }
};

\Rx\Observable::fromIterator($iterator())
    ->subscribe(new DebugSubject());

\Rx\Observable::fromIterator(new ArrayIterator($fruits))
    ->subscribe(new DebugSubject());



\Rx\Observable::fromArray($fruits);
\Rx\Observable::range(0, 5);
