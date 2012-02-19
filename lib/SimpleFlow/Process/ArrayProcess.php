<?php

namespace SimpleFlow\Process;

use SimpleFlow\AbstractElement;
use SimpleFlow\Activity\Activity;
use SimpleFlow\Activity\DefaultActivity;
use SimpleFlow\ElementNotFoundException;
use SimpleFlow\Event\Event;
use SimpleFlow\Transition\DefaultTransition;
use SimpleFlow\Transition\Transition;

/**
 * In memory read-only process implementation based upon straight-forward PHP
 * array activities sparse matrix
 *
 * Serializing this object will loose listeners, listeners are runtime objects
 * and not definition objects
 */
abstract class ArrayProcess extends AbstractElement implements Process, \Serializable
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

        if (!$this->activities[$key] instanceof Activity) {
            if (is_string($this->activities[$key])) {
                $this->activities[$key] = new DefaultActivity($key, $this->activities[$key]);
            } else {
                $this->activities[$key] = new DefaultActivity($key);
            }
        }

        return $this->activities[$key];
    }

    public function getActivities()
    {
        // Force all activities to be built
        foreach ($this->activities as $key => $activity) {
            if (!$activity instanceof Activity) {
                $this->getActivity($key);
            }
        }

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

    public function runTransition($from, $to, ProcessInstance $instance)
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

    public function serialize()
    {
        $data = array();

        $data['k'] = $this->key;

        if ($this->name !== $this->key) {
            $data['n'] = $this->name;
        }

        foreach ($this->activities as $from => $activity) {

            if ($activity instanceof Activity) {
                $data['a'][$from] = $activity->getName();
            } else {
                $data['a'][$from] = $activity;
            }

            if (isset($this->activitySparseMatrix[$from])) {
                foreach ($this->activitySparseMatrix[$from] as $to => $transition) {

                    if ($transition instanceof Transition) {
                        $data['m'][$from][$to] = $transition->getName();
                    } else {
                        $data['m'][$from][$to] = true;
                    }
                }
            }
        }

        return serialize($data);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->key = $data['k'];

        if (isset($data['n'])) {
            $this->name = $data['n'];
        } else {
            $this->name = $this->key;
        }

        if (isset($data['a'])) {
            $this->activities = $data['a'];
        }

        if (isset($data['m'])) {
            $this->activitySparseMatrix = $data['m'];
        }
    }
}
