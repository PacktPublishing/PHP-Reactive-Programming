<?php

require __DIR__ . "/vendor/autoload.php";
require "JSONDecodeOperator.php";
//require "JSONDecodeOperator2.php";
require "DebugSubject.php";

use Rx\Observable;

Observable::just('{"value":42}')
    ->JSONDecode()
//    ->_MyApp_JSONDecode()
    ->subscribe(new DebugSubject());