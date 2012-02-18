<?php

namespace SimpleFlow\Event;

use SimpleFlow\Transition\Transition;

/**
 * Default in memory implementation
 */
class DefaultEvent implements Event
{
    /**
     * @var Transition
     */
    protected $transition;

    /**
     * @var bool
     */
    protected $run = false;

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @var bool
     */
    protected $canceled = false;

    public function getTransition()
    {
        return $this->transition;
    }

    public function stopPropagation()
    {
        $this->stopped = true;

        return $this;
    }

    public function cancelTransition()
    {
        $this->canceled = true;

        return $this;
    }

    public function hasRun()
    {
        return $this->run;
    }

    public function hasBeenStopped()
    {
        return $this->stopped;
    }

    public function hasBeenCanceled()
    {
        return $this->canceled;
    }

    public function __construct(Transition $transition)
    {
        $this->transition = $transition;
    }
}
