<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

Observable::range(1, 3)
    ->subscribe(new DebugSubject());
