<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/DebugSubject.php';

use Rx\ObserverInterface;

class TalkativeDebugSubject extends DebugSubject
{

    public function subscribe(ObserverInterface $observer, $scheduler = null)
    {
        printf("%s%s before subscribe\n", $this->getTime(), $this->id());
        parent::subscribe($observer, $scheduler);
        printf("%s%s after subscribe\n", $this->getTime(), $this->id());
    }

}
