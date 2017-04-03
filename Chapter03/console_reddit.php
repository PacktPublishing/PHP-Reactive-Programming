<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once 'RedditCommand.php';

$application = new Symfony\Component\Console\Application();
$application->setDefaultCommand('reddit');
$application->add(new RedditCommand());
$application->run();
