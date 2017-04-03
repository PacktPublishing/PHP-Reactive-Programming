<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable;
use Rx\Subject\Subject;
use Rx\Subject\ReplaySubject;

class FTPClient
{

    private $conn;
    private $cwd = '/';

    public function __construct($host, $username, $password, $port = 21)
    {
        $this->conn = ftp_connect($host, $port);
        if (!$this->conn) {
            throw new \Exception('Unable to connect to ' . $host);
        }
        if (!ftp_login($this->conn, $username, $password)) {
            throw new \Exception('Unable to login');
        }
    }

    public function chdir($dir)
    {
        $this->cwd = '/' . ltrim($dir, '/');
        if (!ftp_chdir($this->conn, $dir)) {
            throw new \Exception('Unable to change current directory');
        }
    }

    public function listDir()
    {
        return Observable::defer(function() {
            $files = ftp_nlist($this->conn, $this->cwd);
            return Observable::fromArray($files);
        });
    }

    public function listFiles()
    {
        return $this->size($this->listDir())
            ->filter(function($file) {
                return $file['size'] != -1;
            });
    }

    public function listDirectories()
    {
        return $this->size($this->listDir())
            ->filter(function($dir) {
                return $dir['size'] == -1 && $dir['filename'] != '.' && $dir['filename'] != '..';
            })
            ->map(function($dir) {
                return $dir['filename'];
            });
    }

    public function size(Observable $files)
    {
        return Observable::create(function(\Rx\ObserverInterface $observer) use ($files) {
            $files->subscribeCallback(function($filename) use ($observer) {
                $size = ftp_size($this->conn, $filename);
                $observer->onNext(['filename' => $filename, 'size' => $size]);
            });
        });
    }

    public function upload(Observable $files, $mode = FTP_ASCII)
    {
        $subject = new Subject();
        $files->subscribeCallback(function($file) use ($subject, $mode) {
            $fp = fopen($file, 'r');
            $filename = basename($file);

            if (ftp_fput($this->conn, $filename, $fp, $mode)) {
                $subject->onNext($filename);
            } else {
                $e = new Exception('Unable to upload ' . $filename);
                $subject->onError($e);
            }
        });
        return $subject->asObservable();
    }

    public function download(Observable $files, $dir, $mode = FTP_ASCII)
    {
        $subject = new Subject();
        $files->subscribeCallback(function($file) use ($subject, $mode, $dir) {
            $dest = $dir . DIRECTORY_SEPARATOR . $file;
            if (ftp_get($this->conn, $dest, $file, $mode)) {
                $subject->onNext($file);
            } else {
                $e = new Exception('Unable to download ' . $file);
                $subject->onError($e);
            }
        });
        return $subject->asObservable();
    }

    public function close()
    {
        ftp_close($this->conn);
    }

}

