<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;

class MyNodeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node) {
        if (($node instanceof Stmt\If_ ||
                $node instanceof Stmt\ElseIf_ ||
                $node instanceof Stmt\While_
            ) && $this->isAssign($node->cond)) {

            echo $node->getLine() . "\n";
        } elseif ($node instanceof Stmt\For_) {
            $conds = array_filter($node->cond, [$this, 'isAssign']);
            foreach ($conds as $cond) {
                echo $node->getLine() . "\n";
            }
        }
    }

    private function isAssign($cond)
    {
        return $cond instanceof Expr\Assign;
    }
}

$syntax = ParserFactory::PREFER_PHP7;
$parser = (new ParserFactory())->create($syntax);
$code = file_get_contents('_test_source_code.php');

$traverser = new NodeTraverser();
$traverser->addVisitor(new MyNodeVisitor());

$stmts = $parser->parse($code);
$traverser->traverse($stmts);
