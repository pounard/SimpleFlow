<?php

namespace SimpleFlow\Transition;

use SimpleFlow\Element;

interface Transition extends Element
{
    /**
     * Get event listeners
     * @return array Array of callable objects
     */
    public function getListeners();

    /**
     * Add listener to this event. They will receive the raised EventInstance
     * at call time as first parameter
     * @param callable $listener
     * @return Event Chaining self reference
     */
    public function addListener($listener);

    /**
     * Create new event instance and run it over all listeners
     * @return EventInstance Run event instance
     */
    public function run();
}
