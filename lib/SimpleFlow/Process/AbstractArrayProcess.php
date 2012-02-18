<?php

namespace SimpleFlow\Process;

use SimpleFlow\AbstractElement;
use SimpleFlow\Activity\Activity;
use SimpleFlow\ElementNotFoundException;
use SimpleFlow\Event\Event;
use SimpleFlow\Transition\DefaultTransition;
use SimpleFlow\Transition\Transition;

/**
 * In memory read-only process implementation based upon straight-forward PHP
 * array activities sparse matrix
 */
abstract class AbstractArrayProcess extends AbstractElement implements Process
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

    public function getTransition($from, $to)
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

        $transition = $this->activitySparseMatrix[$from][$to];

        if (!$transition instanceof Transition) {
            $key = $from . '-' . $to;
            $transition = $this->activitySparseMatrix[$from][$to] = new DefaultTransition($key);
        }

        return $transition;
    }

    public function addListener($from, $to, $listener)
    {
        $this->getTransition($from, $to)->addListener($listener);

        return $this;
    }

    public function getTransitionsFrom($from)
    {
        if ($from instanceof Activity) {
            $from = $from->getKey();
        }

        if (!isset($this->activities[$from])) {
            throw new ElementNotFoundException($from, $this);
        }

        $ret = array();

        if (isset($this->activitySparseMatrix[$from])) {
            foreach ($this->activitySparseMatrix[$from] as $to => $transition) {
                $ret[] = '';
            }
        }

        return $ret;
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

        $transition = $this->activitySparseMatrix[$from][$to];

        if ($transition instanceof Transition) {

            $event = $transition->run();

            if ($event->hasBeenCanceled()) {
                return false;
            }
        }

        return true;
    }
}
