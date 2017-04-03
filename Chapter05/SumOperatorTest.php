<?php

require_once __DIR__ . '/SumOperator.php';

use Rx\Functional\FunctionalTestCase;

class SumOperatorTest extends FunctionalTestCase {

    public function testSumSuccess() {
        $observer = $this->scheduler->startWithCreate(function () {
            return $this->createHotObservable([
                onNext(150, 3),
                onNext(210, 2),
                onNext(450, 7),
                onCompleted(460),
                onNext(500, 4),
            ])->lift(function() {
                return new SumOperator();
            });
        });

        $this->assertMessages([
            onNext(460, 9),
            onCompleted(460)
        ], $observer->getMessages());
    }

    public function testSumFails() {
        $observer = $this->scheduler->startWithCreate(function () {
            return $this->createHotObservable([
                onNext(150, 3),
                onNext(250, 'abc'),
                onNext(300, 2),
                onCompleted(460)
            ])->lift(function() {
                return new SumOperator();
            });
        });

        $this->assertMessages([
            onError(250, new Exception()),
        ], $observer->getMessages());
    }

}
