<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/ObservableNodeVisitorInterface.php';

use Rx\Subject\Subject;
use PhpParser\NodeVisitorAbstract as Visitor;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;
use PhpParser\PrettyPrinter;

class AssignmentInConditionNodeVisitor extends Visitor implements ObservableNodeVisitorInterface
{
    private $subject;
    private $prettyPrinter;

    public function __construct()
    {
        $this->subject = new Subject();
        $this->prettyPrinter = new PrettyPrinter\Standard();
    }

    public function enterNode(Node $node) {
        if (($node instanceof Stmt\If_ ||
                $node instanceof Stmt\ElseIf_ ||
                $node instanceof Stmt\While_
            ) && $this->isAssign($node->cond)) {

            $this->emitNext($node, $node->cond);
        } elseif ($node instanceof Stmt\For_) {
            $conds = array_filter($node->cond, [$this, 'isAssign']);
            foreach ($conds as $cond) {
                $this->emitNext($node, $cond);
            }
        }
    }

    public function afterTraverse(array $nodes)
    {
        $this->subject->onCompleted();
    }

    public function asObservable()
    {
        return $this->subject->asObservable();
    }

    private function isAssign($cond)
    {
        return $cond instanceof Expr\Assign;
    }

    private function emitNext(Node $node, Expr\Assign $cond)
    {
        $this->subject->onNext([
            'line' => $node->getLine(),
            'expr' => $this->prettyPrinter->prettyPrintExpr($cond),
        ]);
    }

}
