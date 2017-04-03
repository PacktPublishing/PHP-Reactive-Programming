<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Disposable\CompositeDisposable;

class CURLObservable extends \Rx\Observable
{

    private $url;
    private $response;

    public function __construct($url)
    {
        $this->url = $url;
    }

    private function startDownload()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, [$this, 'progress']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36');
        // Disable gzip compression
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip;q=0,deflate,sdch');
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function subscribe(\Rx\ObserverInterface $obsr, $sched = null)
    {
        $disposable = parent::subscribe($obsr, $sched);

        if (null === $sched) {
            $sched = new \Rx\Scheduler\ImmediateScheduler();
        }

        $scheduledDisposable = $sched->schedule(function() use ($obsr) {
            $response = $this->startDownload();

            if ($response) {
                $obsr->onNext($response);
                $obsr->onCompleted();
            } else {
                $e = new \Exception('Unable to download ' . $this->url);
                $obsr->onError($e);
            }
        });

        return new CompositeDisposable([$disposable, $scheduledDisposable]);
    }

    private function progress($res, $downtotal, $down, $uptotal, $up)
    {
        if ($downtotal > 0) {
            $percentage = sprintf("%.2f", $down / $downtotal * 100);
            foreach ($this->observers as $observer) {
                /** @var \Rx\ObserverInterface $observer */
                $observer->onNext(floatval($percentage));
            }
        }
    }
}