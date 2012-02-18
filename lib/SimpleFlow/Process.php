<?php

namespace SimpleFlow;

use BpmnFlow\Activity\Activity;
use BpmnFlow\Element;

/**
 * Read-only finite state machine process interface
 * 
 * This simple implementation does not care about transitions, they are implicit
 * and you will never manipulate BpmnFlow\Transition\Transition instances, you
 * can add listeners to state changes for controlling or listening the changes
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
     * Cast this container as string
     */
    public function __toString();
}
