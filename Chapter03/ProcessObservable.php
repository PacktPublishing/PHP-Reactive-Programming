<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Disposable\CompositeDisposable;
use Rx\Disposable\CallbackDisposable;
use Symfony\Component\Process\Process;

class ProcessObservable extends Observable {

    private $cmd;
    private $pidFile;

    public function __construct($cmd, $pidFile = null) {
        $this->cmd = $cmd;
        $this->pidFile = $pidFile;
    }

    public function subscribe(ObserverInterface $observer, $scheduler = null) {
        $process = new Process($this->cmd);
        $process->start();

        $pid = $process->getPid();
        if ($this->pidFile) {
            file_put_contents($this->pidFile, $pid);
        }

        $disposable = new CompositeDisposable();

        $cancelSchedulerDisposable = $scheduler->schedulePeriodic(function() use ($observer, $process, $pid, &$cancelSchedulerDisposable) {
            if ($process->isRunning()) {
                $observer->onNext($process->getOutput());
            } else {
                $cancelSchedulerDisposable->dispose();
                $observer->onCompleted();
            }
        }, 0, 200);

        $disposable->add($cancelSchedulerDisposable);
        $disposable->add(new CallbackDisposable(function() use ($process) {
            if ($this->pidFile) {
                $process->stop(10, SIGTERM);
                unlink($this->pidFile);
            }
        }));

        return $disposable;
    }

}