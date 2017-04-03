<?php

$input = ['apple', 'banana', 'orange', 'raspberry'];
$input = [5, 6, 6, 9];

function createComparator($threshold) {
    return function($value) use ($threshold) {
        return $value > $threshold;
    };
}

$comparator = createComparator(5);
$filtered = array_filter($input, $comparator);

print_r($filtered);


class MyClass {
    public $fruits;

    public function __construct($arr) {
        $this->fruits = $arr;
    }
}

$count = function() {
    printf("%d\n", count($this->fruits));
};

var_dump(get_class($count));

$obj1 = new MyClass(['apple', 'banana', 'orange']);
$obj2 = new MyClass(['raspberry', 'melon']);

$count->call($obj1);
$count->call($obj2);
