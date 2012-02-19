<?php

namespace SimpleFlow\Event;

use SimpleFlow\Process\ProcessInstance;
use SimpleFlow\Transition\Transition;

/**
 * Raised event instance: those objects are volatile and will exist only the
 * event duration
 *
 * This class is only a data transport object, no business to be done in here
 */
class Event
{
    /**
     * @var Transition
     */
    protected $transition;

    /**
     * @var ProcessInstance
     */
    protected $instance;

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

    /**
     * Get transition being run
     * @return Transition
     */
    public function getTransition()
    {
        return $this->transition;
    }

    /**
     * Get process instance the transition is being run upon
     * @return ProcessInstance
     */
    public function getProcessInstance()
    {
        return $this->instance;
    }

    /**
     * Stop event propagation to further listeners
     * @return Event Chaining self reference
     */
    public function stopPropagation()
    {
        $this->stopped = true;

        return $this;
    }

    /**
     * Cancel transition from being run and stop propagation at the same time
     * @return Event Chaining self reference
     */
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

    /**
     * Default constructor
     * @param Transition $transition
     * @param ProcessInstance $instance
     */
    public function __construct(Transition $transition, ProcessInstance $instance = null)
    {
        $this->transition = $transition;
    }
}
