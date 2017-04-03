<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';
require_once __DIR__ . '/PHPParserOperator.php';
require_once __DIR__ . '/AssignmentInConditionNodeVisitor.php';

use Rx\Observable;

$worker = new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction('phpparser', function(GearmanJob $job) {

    Observable::just($job->workload())
        ->lift(function() {
            $classes = ['AssignmentInConditionNodeVisitor'];
            return new PHPParserOperator($classes);
        })
        ->subscribeCallback(function($results) use ($job) {
            $job->sendComplete(json_encode($results));
        });
});

while ($worker->work()) { }