<?php

namespace SimpleFlow;

class ProcessLockedException
    extends \LogicException
    implements Exception
{
    /**
     * Default constructor
     * @param Process $sender
     */
    public function __construct(Process $sender)
    {
        parent::__construct(sprintf("Processed %s is locked", $sender->getKey()));
    }
}
