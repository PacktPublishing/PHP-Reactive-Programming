<?php

class InvokeExampleClass {
    public function __invoke
    ($x) {
        var_dump(strlen($x));
    }
}
$obj = new InvokeExampleClass();
$obj('apple');
var_dump(is_callable($obj));