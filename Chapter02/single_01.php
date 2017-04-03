<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/PrintObserver.php';

Rx\Observable::just(42)
    ->subscribe(new PrintObserver());
