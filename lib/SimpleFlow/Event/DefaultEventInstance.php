<?php

namespace SimpleFlow\Event;

/**
 * Default in memory implementation
 */
class DefaultEventInstance implements EventInstance
{
    /**
     * @var Event
     */
    protected $event;

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

    public function getEvent()
    {
        return $this->event;
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

    public function __construct(Event $event)
    {
        $this->event = $event;
    }
}
