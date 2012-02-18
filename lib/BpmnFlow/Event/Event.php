<?php

namespace BpmnFlow\Event;

use BpmnFlow\Element;
use BpmnFlow\Transition\Transition;

/**
 * BPMN event describing instance
 */
interface Event extends Element
{
    /**
     * Is this a start state
     * @return bool
     */
    public function isStart();

    /**
     * Is the state final
     * @return bool
     */
    public function isFinal();

    /**
     * Is the event intermediate
     * @return bool
     */
    public function isIntermediate();

    /**
     * Get event transition. Event can be either the start, end or any
     * intermediate event of this transition
     * @return Bpmn\Transition\Transition
     */
    public function getTransition();

    /**
     * Get event listeners
     * @return array Array of callable objects
     */
    public function getListeners();

    /**
     * Add listener to this event. They will receive the raised EventInstance
     * at call time as first parameter
     * @param callable $listener
     * @return BpmnFlow\Event\Event Chaining self reference
     */
    public function addListener($listener);

    /**
     * Create new event instance and run it over all listeners
     * @return EventInstance Run event instance
     */
    public function run();
}
