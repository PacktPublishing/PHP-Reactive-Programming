<?php

use Composer\Autoload\ClassLoader;

class PHPParserWorker extends \Worker
{

    protected $loader;

    public function __construct($loader) {
        $this->loader = $loader;
    }

    public function run() {
        $classLoader = require_once($this->loader);
        $dir = __DIR__;
        $classLoader->addClassMap([
            'DebugSubject' => $dir . '/../Chapter 02/DebugSubject.php',
            'ThreadWorkerOperator' => $dir . '/ThreadWorkerOperator.php',
            'PHPParserThread' => $dir . '/PHPParserThread.php',
            'PHPParserWorker' => $dir . '/PHPParserWorker.php',
            'PHPParserOperator' => $dir . '/PHPParserOperator.php',
        ]);
    }

    public function start(int $options = PTHREADS_INHERIT_ALL) {
        return parent::start(PTHREADS_INHERIT_NONE);
    }

}