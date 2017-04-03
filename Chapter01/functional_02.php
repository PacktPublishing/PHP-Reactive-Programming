<?php

include __DIR__ . '/../vendor/autoload.php';

use function Functional\map;
use function Functional\filter;
use function Functional\reduce_left;

$input = ['apple', 'banana', 'orange', 'raspberry'];

$sum = reduce_left(filter(map($input, function($fruit) {
    return strlen($fruit);
}), function($length) {
    return $length > 5;
}), function($val, $i, $col, $reduction) {
    return $val + $reduction;
});

printf("sum: %d\n", $sum);