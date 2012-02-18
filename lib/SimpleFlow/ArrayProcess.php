<?php

namespace SimpleFlow;

use BpmnFlow\AbstractElement;
use BpmnFlow\Activity\Activity;
use BpmnFlow\ElementAlreadyExistsException;
use BpmnFlow\ElementNotFoundException;
use BpmnFlow\Event\DefaultEvent;
use BpmnFlow\Event\Event;

/**
 * In memory read-write process implementation based upon straight-forward PHP
 * array activities sparse matrix.
 */
class ArrayProcess extends AbstractElement implements WritableProcess
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

    /**
     * @var bool
     */
    protected $locked = false;

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

    public function getEvent($from, $to)
    {
        if (!$this->canTransition($from, $to)) {
            throw new TransitionNotAllowedException($from, $to, $this);
        }

        if ($from instanceof Activity) {
            $from = $from->getKey();
        }
        if ($to instanceof Activity) {
            $to = $to->getKey();
        }

        $event = $this->activitySparseMatrix[$from][$to];

        if (!$event instanceof Event) {
            $event = $this->activitySparseMatrix[$from][$to] = new DefaultEvent($from . '->' . $to);
        }

        return $event;
    }

    public function canTransition($from, $to)
    {
        if ($from instanceof Activity) {
            $from = $from->getKey();
        }
        if ($to instanceof Activity) {
            $to = $to->getKey();
        }

        if (!isset($this->activities[$from])) {
            throw new ElementNotFoundException($from, $this);
        }
        if (!isset($this->activities[$to])) {
            throw new ElementNotFoundException($to, $this);
        }

        return isset($this->activitySparseMatrix[$from][$to]);
    }

    public function runTransition($from, $to)
    {
        if (!$this->canTransition($from, $to)) {
            throw new TransitionNotAllowedException($from, $to, $this);
        }

        if ($from instanceof Activity) {
            $from = $from->getKey();
        }
        if ($to instanceof Activity) {
            $to = $to->getKey();
        }

        $event = $this->activitySparseMatrix[$from][$to];

        if ($event instanceof Event) {

            $eventInstance = $event->run();

            if ($eventInstance->hasBeenCanceled()) {
                return false;
            }
        }

        return true;
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function lock()
    {
        $this->locked = true;

        return $this;
    }

    public function addActivity(Activity $activity, $replace = false)
    {
        if ($this->isLocked()) {
            throw new ProcessLockedException($this);
        }

        $key = $activity->getKey();

        if (!$replace && isset($this->activities[$key])) {
            throw new ElementAlreadyExistsException($this->activities[$key], $activity, $this);
        }

        $this->activities[$key] = $activity;

        return $this;
    }

    public function setTransition($from, $to, Event $event = null)
    {
        if ($this->isLocked()) {
            throw new ProcessLockedException($this);
        }

        if ($this->canTransition($from, $to)) {
            throw new ElementAlreadyExistsException();
        }

        if ($from instanceof Activity) {
            $from = $from->getKey();
        }
        if ($to instanceof Activity) {
            $to = $to->getKey();
        }

        if (!isset($event)) {
            $event = true;
        }

        $this->activitySparseMatrix[$from][$to] = $event;

        return $this;
    }
}
