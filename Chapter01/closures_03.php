<?php

$str = 'Hello, World';

$func = function() use ($str) {
    $str .= '!!!';
    echo $str . "\n";
};
$func();

echo $str . "\n";

$func2 = function() use (&$str) {
    $str .= '???';
    echo $str . "\n";
};
$func2();

echo $str . "\n";
