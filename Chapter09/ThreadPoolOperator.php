<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/AbstractRxThread.php';

use Rx\ObservableInterface;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;
use Rx\Operator\OperatorInterface;
use Rx\Observer\CallbackObserver;
use Rx\Disposable\BinaryDisposable;

class ThreadPoolOperator implements OperatorInterface
{

    private $pool;

    public function __construct($numThreads = 4, $workerClass = Worker::class, $workerArgs = [])
    {
        $this->pool = new Pool($numThreads, $workerClass, $workerArgs);
    }

    public function __invoke(ObservableInterface $observable, ObserverInterface $observer, SchedulerInterface $scheduler = null)
    {
        $callbackObserver = new CallbackObserver(function(AbstractRxThread $task) {
                /** @var AbstractRxThread $task */
                $this->pool->submit($task);
            },
            [$observer, 'onError'],
            [$observer, 'onCompleted']
        );

        $dis1 = $scheduler->schedulePeriodic(function() use ($observer) {
            $this->pool->collect(function(AbstractRxThread $task) use ($observer) {
                /** @var AbstractRxThread $task */
                if ($task->isDone()) {
//                    foreach ($task->getResult() as $result) {
                        $observer->onNext($task->getResult());
//                    }
                    return true;
                } else {
                    return false;
                }
            });
        }, 0, 1);

        $dis2 = $observable->subscribe($callbackObserver);
        $disposable = new BinaryDisposable($dis1, $dis2);

        return $disposable;
    }

}