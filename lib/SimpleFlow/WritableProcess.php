<?php

namespace SimpleFlow;

use BpmnFlow\Activity\Activity;

/**
 * Writable finite state machine process interface.
 */
interface WritableProcess extends Process
{
    /**
     * Add an new orphaned activity
     * @param Activity $activity 
     * @param bool $replace Replace existing if exist
     * @throws ElementAlreadyExistsException If not in replace mode and if an
     * activity with the same key already exists
     */
    public function addActivity($activity, $replace = false);
}
