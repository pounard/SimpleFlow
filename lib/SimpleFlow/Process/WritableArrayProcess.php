<?php

namespace SimpleFlow\Process;

use SimpleFlow\Activity\Activity;
use SimpleFlow\ElementAlreadyExistsException;
use SimpleFlow\Transition\Transition;

/**
 * In memory read-write process implementation based upon straight-forward PHP
 * array activities sparse matrix
 */
class WritableArrayProcess extends ArrayProcess implements WritableProcess
{
    public function isLocked()
    {
        return $this->locked;
    }

    public function lock()
    {
        $this->locked = true;

        return $this;
    }

    public function addActivity($key, $name = null, $replace = false)
    {
        if ($this->isLocked()) {
            throw new ProcessLockedException($this);
        }

        if (!$replace && isset($this->activities[$key])) {
            throw new ElementAlreadyExistsException($this->activities[$key], $activity, $this);
        }

        if (isset($name)) {
            $this->activities[$key] = $name;
        } else {
            $this->activities[$key] = true;
        }

        return $this;
    }

    public function setTransition($from, $to, Transition $transition = null)
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

        if (!isset($transition)) {
            $transition = true;
        }

        $this->activitySparseMatrix[$from][$to] = $transition;

        return $this;
    }
}
