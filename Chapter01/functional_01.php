<?php

$input = ['apple', 'banana', 'orange', 'raspberry'];

// imperative programming
$sum = 0;

foreach ($input as $fruit) {
    $length = strlen($fruit);
    if ($length > 5) {
        $sum += $length;
    }
}

printf("sum: %d\n", $sum);


// functional programming
$lengths = array_map(function($fruit) {
    return strlen($fruit);
}, $input);
$filtered = array_filter($lengths, function($length) {
    return $length > 5;
});
$sum = array_reduce($filtered, function($a, $b) {
    return $a + $b;
});

printf("sum: %d\n", $sum);


$sum = array_reduce(array_filter(array_map(function($fruit) {
    return strlen($fruit);
}, $input), function($length) {
    return $length > 5;
}), function($a, $b) {
    return $a + $b;
});

printf("sum: %d\n", $sum);