<?php

namespace SimpleFlow\Event;

/**
 * Raised event instance: those objects are volatile and will exist only the
 * event duration
 *
 * This class is only a data transport object, no business to be done in here
 */
interface Event
{
    /**
     * Get event description
     * @return Event
     */
    public function getTransition();

    /**
     * Stop event propagation to further events
     * @return EventInstance Chaining self reference
     */
    public function stopPropagation();

    /**
     * Cancel transition phase and avoid the process to advance to next
     * activity or end event, it will also stop propagation to further
     * events
     * @return EventInstance Chaining self reference
     */
    public function cancelTransition();

    /**
     * Does this event instance has run
     * @return bool
     */
    public function hasRun();

    /**
     * Does this event instance has been stopped
     * @return bool
     */
    public function hasBeenStopped();

    /**
     * Does this event instance has been canceled
     * @return bool
     */
    public function hasBeenCanceled();
}
