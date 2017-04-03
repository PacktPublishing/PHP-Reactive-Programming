<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable;
use Rx\Observer\CallbackObserver;

$fruits = ['apple', 'banana', 'orange', 'raspberry'];

$observer = new CallbackObserver(
    function($value) {
        echo "Next: $value\n";
    },
    function(\Exception $err) {
        $msg = $err->getMessage();
        echo "Error: $msg\n";
    },
    function() {
        echo "Complete\n";
    }
);

Observable::fromArray($fruits)
    ->map(function($value) {
        return strlen($value);
    })
    ->filter(function($len) {
        return $len > 5;
    })
    ->subscribe($observer);