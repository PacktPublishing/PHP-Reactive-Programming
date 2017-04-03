<?php

ob_start();

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://stackoverflow.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progress');
curl_setopt($ch, CURLOPT_NOPROGRESS, false);
curl_setopt($ch, CURLOPT_HEADER, 0);
$html = curl_exec($ch);
curl_close($ch);


function progress($resource, $download_size, $downloaded, $upload_size, $uploaded)
{
    if ($download_size > 0) {
        printf("%.2f\n", $downloaded / $download_size * 100);
    }
    ob_flush();
    usleep(100 * 1000);
}
