<?php

namespace SimpleFlow\Event;

use SimpleFlow\AbstractElement;
use SimpleFlow\ElementNotFoundException;
use SimpleFlow\Transition\Transition;

/**
 * Default in memory event implementation
 *
 * FIXME: Add listener prioritizing
 */
class DefaultEvent extends AbstractElement implements Event
{
    /**
     * @var bool
     */
    protected $isStart = false;

    /**
     * @var bool
     */
    protected $isFinal = false;

    /**
     * @var Transition
     */
    protected $transition;

    /**
     * @var array
     */
    protected $listeners = array();

    public function isStart()
    {
        return $this->isStart;
    }

    public function isFinal()
    {
        return $this->isFinal;
    }

    public function isIntermediate()
    {
        return !($this->isStart || $this->isFinal);
    }

    public function getTransition()
    {
        if (!isset($this->transition)) {
            throw new ElementNotFoundException();
        }

        return $transition;
    }

    public function getListeners()
    {
        return $this->listeners;
    }

    public function addListener($listener)
    {
        if (!is_callable($listener)) {
            throw new \InvalidArgumentException("Argument is not a valid callable instance");
        }

        $this->listeners[] = $listener;

        return $this;
    }

    public function run()
    {
        $eventInstance = new DefaultEventInstance($this);

        foreach ($this->listeners as $listener) {

            $listener($eventInstance);

            if ($eventInstance->hasBeenStopped()) {
                break;
            }
        }

        return $eventInstance;
    }
}
