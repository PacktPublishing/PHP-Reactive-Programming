<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Chapter 02/CURLObservable.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CURLCommand extends Command
{
    protected function configure()
    {
        $this->setName('curl');
        $this->setDescription('Wrapped CURLObservable as a standalone app');
        $this->addArgument('url', InputArgument::REQUIRED, 'URL to download');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $returnCode = 0;
        (new CURLObservable($input->getArgument('url')))
            ->subscribeCallback(function($response) use ($output) {
                if (!is_float($response)) {
                    $output->write($response);
                }
            }, function() use (&$returnCode) {
                $returnCode = 1;
            });

        return $returnCode;
    }
}

$application = new Symfony\Component\Console\Application();
$application->add(new CURLCommand());
$application->run();
