<?php

$obj = new stdClass();
$obj->prop = 'foo';

$obj2 = $obj;
printf("%d\n", $obj === $obj2);


class TestClass
{
    public $obj;
    public $objCopy;

    public function copyObj()
    {
        $this->objCopy = $this->obj;
        $this->objCopy->prop2 = 'bar';
    }
}

$testObj = new TestClass();
$testObj->obj = $obj;
$testObj->copyObj();
printf("%d\n", $obj === $testObj->objCopy);
print_r($obj);
