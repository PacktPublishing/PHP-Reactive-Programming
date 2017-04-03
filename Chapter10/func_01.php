<?php

class MyClass {
    public function __invoke($a, $b)
    {
        return $a * $b;
    }
}


$obj = new MyClass();
var_dump($obj(3, 4));
