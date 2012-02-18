<?php

namespace SimpleFlow;

use BpmnFlow\Activity\Activity;

/**
 * In memory read-write process implementation based upon straight-forward PHP
 * array activities sparse matrix.
 */
class ArrayProcess implements WritableProcess
{
    /**
     * Finite state machine represented as an activities sparse matrix.
     * @var array
     */
    protected $activitySparseMatrix = array();

    /**
     * Actitives map based upon their keys
     * @var array
     */
    protected $activities = array();

    public function addActivity(Activity $activity, $replace = false)
    {
        $key = $activity->getKey();

        if (!$replace && isset($this->activities[$key])) {
            throw new ElementAlreadyExistsException($this->activities[$key], $activity, $this);
        }
    }

    public function getActivity($key)
    {
        if (!isset($this->activities[$key])) {
            throw new ElementNotFoundException($key, $this);
        }

        return $this->activities[$key];
    }

    public function getActivities()
    {
        return $this->activities;
    }
}
