<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/AbstractRxThread.php';
require_once __DIR__ . '/PHPParserOperator.php';
require_once __DIR__ . '/AssignmentInConditionNodeVisitor.php';

use Rx\Observable;

class PHPParserThread extends AbstractRxThread
{
    private $filenames;

    public function __construct($filename)
    {
        $this->filenames = (array)(is_array($filename) ? $filename : [$filename]);
        /** @var Volatile result */
        $this->result = [];
    }

    public function run()
    {
        $last = 0;
        Observable::fromArray($this->filenames)
            ->lift(function() {
                $classes = ['AssignmentInConditionNodeVisitor'];
                return new PHPParserOperator($classes);
            })
            ->subscribeCallback(function ($results) use (&$last) {
                $this->result[$last++] = (array)[
                    'file' => $results['file'],
                    'results' => $results['results'],
                ];
            }, null, function() {
                $this->markDone();
            });
    }
}