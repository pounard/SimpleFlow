<?php

namespace SimpleFlow\Process;

use SimpleFlow\AbstractElement;
use SimpleFlow\Activity\Activity;
use SimpleFlow\ElementAlreadyExistsException;
use SimpleFlow\ElementNotFoundException;
use SimpleFlow\Event\DefaultEvent;
use SimpleFlow\Event\Event;

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
