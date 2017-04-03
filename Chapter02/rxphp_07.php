<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DebugSubject.php';
require_once __DIR__ . '/JSONDecodeOperator.php';

\Rx\Observable::just('{"value":42}')
    ->lift(function() {
        return new JSONDecodeOperator();
    })
    ->subscribe(new DebugSubject());


\Rx\Observable::just('NA')
    ->lift(function() {
        return new JSONDecodeOperator();
    })
    ->subscribe(new DebugSubject());
