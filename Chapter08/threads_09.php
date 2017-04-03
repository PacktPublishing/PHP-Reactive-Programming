<?php

$ref = new ReflectionClass(Thread::class);
print_r($ref->getInterfaces());

$ref = new ReflectionClass(Volatile::class);
print_r($ref->getInterfaces());
print_r($ref->getExtension());

