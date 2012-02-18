<?php

namespace SimpleFlow\Process;

use BpmnFlow\Activity\Activity;

use SimpleFlow\Exception;

class TransitionNotAllowedException
    extends \InvalidArgumentException
    implements Exception
{
    /**
     * Default constructor
     * @param scalar|Activity $from Activity object or key
     * @param scalar|Activity $to Activity object or key
     * @param Process $process
     */
    public function __construct($from, $to, Process $process)
    {
        if ($from instanceof Activity) {
            $from = $from->getKey();
        }

        if ($to instanceof Activity) {
            $to = $to->getKey();
        }

        parent::__construct(sprintf("Transition from %s to %s is not allowed in process %s", $from, $to, $process->getKey()));
    }
}
