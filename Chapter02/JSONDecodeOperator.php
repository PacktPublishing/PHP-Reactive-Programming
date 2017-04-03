<?php

use Rx\Observer\CallbackObserver;
use Rx\ObservableInterface as ObservableI;
use Rx\ObserverInterface as ObserverI;
use Rx\SchedulerInterface as SchedulerI;
use Rx\Operator\OperatorInterface as OperatorI;

class JSONDecodeOperator implements OperatorI
{
    public function __invoke(ObservableI $observable, ObserverI $observer, SchedulerI $scheduler = null)
    {
        $callbackObserver = new CallbackObserver(
            function ($value) use ($observer) {
                $decoded = json_decode($value, true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $observer->onNext($decoded);
                } else {
                    $msg = json_last_error_msg();
                    $e = new InvalidArgumentException($msg);
                    $observer->onError($e);
                }
            },
            [$observer, 'onError'],
            [$observer, 'onCompleted']
        );

        return $observable->subscribe($callbackObserver, $scheduler);
    }
}