<?php

$client = new GearmanClient();
$client->addServer('127.0.0.1');
$client->setTimeout(3000);

$length = @$client->doNormal('strlen', 'Hello World!');
if (empty($length)) {
    echo "timeout\n";
} else {
    var_dump(intval($length));
}
