<?php

namespace SimpleFlow\Transition;

interface Transition
{
    /**
     * Get human readable name.
     * @return string
     */
    public function getName();

    /**
     * Get event listeners
     * @return array Array of callable objects
     */
    public function getListeners();

    /**
     * Add listener to this event. They will receive the raised Event
     * at call time as first parameter
     * @param callable $listener
     * @return Transition Chaining self reference
     */
    public function addListener($listener);

    /**
     * Create new event instance and run it over all listeners
     * @param ProcessInstance $instance Instance on which the transition is run
     * @return Event Run event instance
     */
    public function run(ProcessInstance $instance = null);
}
