<?php

namespace SimpleFlow;

class ElementNotFoundException
    extends \InvalidArgumentException
    implements FlowException
{
    /**
     * Default constructor
     * @param scalar $key
     * @param mixed $container Must be castable as string
     */
    public function __construct($key = null, $sender = null)
    {
        /* if (isset($container)) {
            // @TODO
            $message = "TODO";
        } else { */
            if (isset($key)) {
                $message = sprintf("%s already exists");
            } else {
                $message = "Element already exists";
            }
        /* } */

        parent::__construct($message);
    }
}
