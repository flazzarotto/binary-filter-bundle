<?php

namespace Flazzarotto\BinaryFilterBundle\BinaryFilter;

class FilterException extends \Exception {

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        if (!strlen($message)) {
            $message = "Path to filtered image has not been initialised or is not writable";
        }
        parent::__construct($message, $code, $previous);
    }

}
