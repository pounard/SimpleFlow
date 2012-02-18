<?php

namespace SimpleFlow\Process;

use SimpleFlow\Activity\Activity;
use SimpleFlow\ElementAlreadyExistsException;
use SimpleFlow\ElementNotFoundException;
use SimpleFlow\Event\Event;

/**
 * Writable finite state machine process interface.
 */
interface WritableProcess extends Process
{
    /**
     * Does this instance is locked
     * @return bool
     */
    public function isLocked();

    /**
     * Lock this instance. Processes cannot be explictely unlocked since they
     * may be rebuilt from caches or coming from alternative storage backends
     * case in which the end user would not be responsible for the instance
     * definition
     * @return WritableProcess Chaining self reference
     */
    public function lock();

    /**
     * Add an new orphaned activity
     * @param Activity $activity 
     * @param bool $replace Replace existing if exist
     * @return WritableProcess Chaining self reference
     * @throws ElementAlreadyExistsException If not in replace mode and if an
     * activity with the same key already exists
     * @throws ProcessLockedException If process is locked
     */
    public function addActivity(Activity $activity, $replace = false);

    /**
     * Add a new transition
     * @param scalar|Activity $from Activity object or key
     * @param scalar|Activity $to Activity object or key
     * @param Event $event
     * @return WritableProcess Chaining self reference
     * @throws ElementAlreadyExistsException If transition already exists
     * @throws ProcessLockedException If process is locked
     */
    public function setTransition($from, $to, Event $event = null);
}
