<?php
$source = fopen('textfile.txt', 'r');
echo get_resource_type($source) . "\n";

$xml = xml_parser_create();
echo get_resource_type($xml) . "\n";

$curl = curl_init();
echo get_resource_type($curl) . "\n";
