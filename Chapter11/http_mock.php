<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

$data = '[{"name": "John"},{"name": "Bob"},{"name": "Dan"}]';

Observable::just($data)
    ->map(function($value) {
        return json_decode($value, true);
    })
    ->concatMap(function($value) {
        return Observable::fromArray($value);
    })
    ->subscribe(new DebugSubject());