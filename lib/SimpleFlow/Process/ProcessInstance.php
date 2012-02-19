<?php

namespace SimpleFlow\Process;

use SimpleFlow\Activity\Activity;
use SimpleFlow\ElementNotFoundException;

/**
 * Process instance, stateful object
 */
interface ProcessInstance
{
    /**
     * Get process this instance is attached to
     * @return Process
     */
    public function getProcess();

    /**
     * Move to given activity
     * @param scalar|Activity Activity instance or key
     * @return bool True in case of success, false if a listener canceled the
     * transition while running the event
     * @throws ElementNotFoundException
     * @throws TransitionNotAllowedExeception
     */
    public function transitionTo($activity);

    /**
     * Does this instance can proceed to the given activity
     * @param scalar|Activity Activity instance or key
     * @return bool
     * @throws ElementNotFoundException
     */
    public function canTransitionTo($activity);

    /**
     * Get activity this instance currently is
     * @return Activity
     */
    public function getActivity();
}
