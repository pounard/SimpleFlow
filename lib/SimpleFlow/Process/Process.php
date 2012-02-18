<?php

namespace SimpleFlow\Process;

use SimpleFlow\Activity\Activity;
use SimpleFlow\Element;
use SimpleFlow\Event\Event;

/**
 * Read-only simple finite state machine process interface
 *
 * This simple implementation is Transition-less, only events will be attached
 * to the activity matrix, one per possible transition
 */
interface Process extends Element
{
    /**
     * Get specific activity
     * @param scalar $key Activity key
     * @return Activity
     * @throws ElementNotFoundException If activity does not exist
     */
    public function getActivity($key);

    /**
     * Get all activities
     * @return array Array of Activity instances keyed with
     * activities keys
     */
    public function getActivities();

    /**
     * Get event at the given transition
     * @param scalar|Activity $from Activity object or key
     * @param scalar|Activity $to Activity object or key
     * @return Event
     * @throws ElementNotFoundException
     * @throws TransitionNotAllowedException
     */
    public function getEvent($from, $to);

    /**
     * Alias for Process::getEvent()::addListener()
     * @param scalar|Activity $from Activity object or key
     * @param scalar|Activity $to Activity object or key
     * @param callable $listener
     * @return Process Chaining self reference
     * @throws ElementNotFoundException
     * @throws TransitionNotAllowedException
     */
    public function addListener($from, $to, $listener);

    /**
     * Does this process can proceed to the change from given activity to given
     * activity
     * @param scalar|Activity $from Activity object or key
     * @param scalar|Activity $to Activity object or key
     * @return bool
     * @throws ElementNotFoundException If one of the activities does not exist
     */
    public function canTransition($from, $to);

    /**
     * Run transition from the given activity to the given activity: any
     * attached listeners or event will be run
     * @param scalar|Activity $from Activity object or key
     * @param scalar|Activity $to Activity object or key
     * @throws ElementNotFoundException If one of the activities does not exist
     * @throws TransitionNotAllowedException If the transition is not allowed
     */
    public function runTransition($from, $to);
}
