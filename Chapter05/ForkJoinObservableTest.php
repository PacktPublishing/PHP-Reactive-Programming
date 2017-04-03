<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/ForkJoinObservable.php';

use Rx\Functional\FunctionalTestCase;

class ForkJoinObservableTest extends FunctionalTestCase
{

  public function testJoinObservables()
  {
    $observer = $this->scheduler->startWithCreate(function () {
      return new ForkJoinObservable([
          $this->createHotObservable([
              onNext(200, 1),
              onNext(300, 2),
              onNext(400, 3),
              onCompleted(500),
              onNext(600, 4),
          ]),
          $this->createHotObservable([
              onNext(200, 8),
              onNext(300, 7),
              onNext(400, 6),
              onCompleted(800),
          ])
      ]);
    });

    $this->assertMessages([
        onNext(800, [3, 6]),
        onCompleted(800)
    ], $observer->getMessages());
  }

  public function testJoinObservablesNeverCompletes()
  {
    $observer = $this->scheduler->startWithCreate(function () {
      return new ForkJoinObservable([
          $this->createHotObservable([
              onNext(200, 1),
              onNext(300, 2),
              onCompleted(500),
          ]),
          $this->createHotObservable([
              onNext(200, 8),
              onNext(300, 7),
          ])
      ]);
    });

    $this->assertMessages([
    ], $observer->getMessages());
  }

}