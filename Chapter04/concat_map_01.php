<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once '../Chapter 02/CURLObservable.php';

use Rx\Observable;

function createCURLObservable($num) {
    $url = 'http://httpbin.org/get?num=' . $num;
    echo "$url\n";
    return (new CURLObservable($url))
        ->filter(function($response) {
            return is_string($response);
        });
}

$source = Observable::emptyObservable()
    ->concat(createCURLObservable(rand(1, 100)))
    ->concatMap(function($response) {
        $json = json_decode($response, true);
        return createCURLObservable(2 * $json['args']['num']);
    })
    ->concatMap(function($response) {
        $json = json_decode($response, true);
        return createCURLObservable(2 * $json['args']['num']);
    })
    ->subscribe(new DebugSubject());
