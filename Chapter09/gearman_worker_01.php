<?php

$worker = new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction('strlen', function(GearmanJob $job) {
    echo 'new job: ' . $job->workload()
        . ' (' . $job->workloadSize() . ")\n";
    return strlen($job->workload());
});

while ($worker->work()) { }