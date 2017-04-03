<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/PHPParserOperator.php';
require_once __DIR__ . '/AssignmentInConditionNodeVisitor.php';

use Rx\Observable;

Observable::fromArray(['_test_source_code.php'])
    ->lift(function() {
        $classes = ['AssignmentInConditionNodeVisitor'];
        return new PHPParserOperator($classes);
    })
    ->subscribeCallback(function($results) {
        print_r($results);
    });
