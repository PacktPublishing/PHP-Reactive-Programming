<?php

include __DIR__ . "/vendor/autoload.php";

use Interop\Async\Loop;
use WyriHaximus\React\AsyncInteropLoop\ReactDriverFactory;

Loop::setFactory(ReactDriverFactory::createFactory());

Loop::delay(1000, function() {
    echo "second\n";
});
Loop::delay(500, function() {
    echo "first\n";
});

Loop::get()->run();