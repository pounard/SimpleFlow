<?php

namespace SimpleFlow\Process;

use SimpleFlow\AbstractElement;
use SimpleFlow\Activity\Activity;
use SimpleFlow\Activity\DefaultActivity;
use SimpleFlow\ElementAlreadyExistsException;
use SimpleFlow\ElementNotFoundException;
use SimpleFlow\Transition\Transition;

/**
 * In memory read-write process implementation based upon straight-forward PHP
 * array activities sparse matrix
 */
class ArrayProcess extends AbstractArrayProcess implements WritableProcess
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

        $this->activities[$key] = new DefaultActivity($key, $name);

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
