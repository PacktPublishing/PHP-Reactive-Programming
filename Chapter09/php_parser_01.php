<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpParser\ParserFactory;

$code = file_get_contents('_test_source_code.php');

$syntax = ParserFactory::PREFER_PHP7;
$parser = (new ParserFactory())->create($syntax);
$stmts = $parser->parse($code);

print_r($stmts);