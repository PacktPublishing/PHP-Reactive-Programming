<?php

use Rx\Operator\OperatorInterface;
use Rx\ObservableInterface;
use Rx\SchedulerInterface;
use Rx\ObserverInterface;
use Rx\Observer\CallbackObserver;

class SumOperator implements OperatorInterface  {

    private $sum = 0;

    public function __invoke(ObservableInterface $observable, ObserverInterface $observer, SchedulerInterface $scheduler = null) {
        $observable->subscribe(new CallbackObserver(
            function($value) use ($observer) {
                if (is_int($value)) {
                    $this->sum += $value;
                } else {
                    $observer->onError(new Exception());
                }
            },
            [$observer, 'onError'],
            function() use ($observer) {
                $observer->onNext($this->sum);
                $observer->onCompleted();
            }
        ));
    }

}