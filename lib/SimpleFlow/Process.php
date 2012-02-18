<?php

namespace SimpleFlow;

use BpmnFlow\Activity\Activity;
use BpmnFlow\Element;

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
     * @return BpmnFlow\Activity\Activity
     * @throws ElementNotFoundException If activity does not exist
     */
    public function getActivity($key);

    /**
     * Get all activities
     * @return array Array of BpmnFlow\Activity\Activity instances keyed with
     * activities keys
     */
    public function getActivities();

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
