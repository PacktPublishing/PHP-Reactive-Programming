<?php

namespace MyApp\Rx\Operator;

use Rx\ObservableInterface as ObservableI;
use Rx\ObserverInterface as ObserverI;
use Rx\Operator\OperatorInterface as OperatorI;
use Rx\DisposableInterface as DisposableI;

class JSONDecodeOperator implements OperatorI
{
    public function __invoke(ObservableI $observable, ObserverI $observer): DisposableI
    {
        return $observable->subscribe(
            function ($value) use ($observer) {
                $decoded = json_decode($value, true);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $observer->onNext($decoded);
                } else {
                    $msg = json_last_error_msg();
                    $e = new \InvalidArgumentException($msg);
                    $observer->onError($e);
                }
            },
            [$observer, 'onError'],
            [$observer, 'onCompleted']
        );
    }
}