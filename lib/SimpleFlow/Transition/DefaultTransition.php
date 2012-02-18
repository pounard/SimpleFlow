<?php

namespace SimpleFlow\Transition;

use SimpleFlow\AbstractElement;
use SimpleFlow\Event\DefaultEvent;

/**
 * Default in memory event implementation
 *
 * FIXME: Add listener prioritizing
 */
class DefaultTransition extends AbstractElement implements Transition
{
    /**
     * @var array
     */
    protected $listeners = array();

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
        $event = new DefaultEvent($this);

        foreach ($this->listeners as $listener) {

            $listener($event);

            if ($event->hasBeenStopped()) {
                break;
            }
        }

        return $event;
    }
}
