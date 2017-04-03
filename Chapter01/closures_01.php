<?php

$input = ['apple', 'banana', 'orange', 'raspberry'];

class MyClass {
    public $fruits;

    public function __construct($arr) {
        $this->fruits = $arr;
    }
}

$count = function() {
    printf("%d\n", count($this->fruits));
};

$obj1 = new MyClass(['apple', 'banana', 'orange']);
$obj2 = new MyClass(['raspberry', 'melon']);

$count->call($obj1);
$count->call($obj2);
