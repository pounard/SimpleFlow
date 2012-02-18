<?php

namespace BpmnFlow;

use BpmnFlow\Element;

class ElementAlreadyExistsException
    extends \InvalidArgumentException
    implements Exception
{
    /**
     * Default constructor
     * @param Element $original
     * @param Element $new
     * @param mixed $container Must be castable as string
     */
    public function __construct(Element $original = null, Element $new = null, $sender = null)
    {
        if (!isset($original)) {
            $message = "Unknown original element already exists";
        } else {
            /* if (isset($container)) {
                // @TODO
                $message = "TODO";
            } else { */
                if (isset($new)) {
                    $message = sprintf("%s with key %s already exists while replacing with %s",
                        get_class($original), $original->getKey(), get_class($new));
                } else {
                    $message = sprintf("%s with key %s already exists",
                        get_class($original), $original->getKey());
                }
            /* } */
        }

        parent::__construct($message);
    }
}
