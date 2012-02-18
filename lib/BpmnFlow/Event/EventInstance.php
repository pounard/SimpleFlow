<?php

namespace BpmnFlow\Event;

/**
 * BPMN raised event instance: those objects are volatile and will exist only
 * the event duration
 */
interface EventInstance
{
    /**
     * Get event description
     * @return Event
     */
    public function getEvent();

    /**
     * Stop event propagation to further events
     */
    public function stopPropagation();

    /**
     * Cancel transition phase and avoid the process to advance to next
     * activity or end event, it will also stop propagation to further
     * events
     */
    public function cancelTransition();
}
