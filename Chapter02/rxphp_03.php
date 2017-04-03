<?php

require_once __DIR__ . '/../vendor/autoload.php';

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

class PrintObserver extends \Rx\Observer\AbstractObserver {
    protected function completed() {
        print("Completed\n");
    }

    protected function next($value) {
        print(sprintf("Next: %s\n", $value));
    }

    protected function error(Exception $error) {
        print("Error\n");
    }
}

$source = \Rx\Observable::fromArray($fruits);
$source->subscribe(new PrintObserver());
