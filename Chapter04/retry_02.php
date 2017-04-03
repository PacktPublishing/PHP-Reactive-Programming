<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

Rx\Observable::range(1,3)
    ->doOnEach(new DebugSubject('in'))
    ->map(function($val) {
        if ($val == 2) {
            throw new \Exception('$val == 2');
        } else {
            return $val;
        }
    })
    ->doOnEach(new DebugSubject('middle'))
//    ->doOnNext(function($val) {
//        print "doOnNext: $val\n";
//    })
    ->retry(2)
    ->subscribe(new DebugSubject('out'))
;