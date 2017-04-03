<?php

class DebugSubject extends \Rx\Subject\Subject {

    private $maxLen;
    private $identifier;

    public function __construct($identifier = null, $maxLen = 64)
    {
        $this->identifier = $identifier;
        $this->maxLen = $maxLen;
    }

    public function onCompleted() {
        printf("%s%s onCompleted\n", $this->getTime(), $this->id());
        parent::onCompleted();
        $this->dispose();
    }

    public function onNext($value) {
        if ($this->isDisposed()) {
            return;
        }
        $type = is_object($value) ? get_class($value) : gettype($value);

        if (is_object($value) && method_exists($value, '__toString')) {
            $str = (string)$value;
        } elseif (is_object($value)) {
            $str = get_class($value);
        } elseif (is_array($value)) {
            $str = json_encode($value);
        } else {
            $str = $value;
        }

        if (is_string($str) && strlen($str) > $this->maxLen) {
            $str = substr($str, 0, $this->maxLen) . '...';
        }

        printf("%s%s onNext: %s (%s)\n", $this->getTime(), $this->id(), $str, $type);
        parent::onNext($value);
    }

    public function onError(\Throwable $error) {
        $msg = $error->getMessage();
        printf("%s%s onError (%s): %s\n", $this->getTime(), $this->id(), get_class($error), $msg);
//        throw $error;
//        parent::onError($error);
        $this->dispose();
    }

    private function getTime() {
        return date('H:i:s');
    }

    private function id() {
        return ' [' . $this->identifier . ']';
    }
}
