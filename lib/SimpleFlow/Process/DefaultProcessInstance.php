<?php

namespace SimpleFlow\Process;

use SimpleFlow\Activity\Activity;

class DefaultProcessInstance implements ProcessInstance
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * Current activity key
     * @var scalar
     */
    protected $activityKey;

    public function getProcess()
    {
        return $this->process;
    }

    public function transitionTo($activity)
    {
        if ($this->process->runTransition($this->activityKey, $activity, $this)) {

            if ($activity instanceof Activity) {
                $this->activityKey = $activity->getKey();
            } else {
                $this->activityKey = $activity;
            }

            return true;
        } else {
            return false;
        }
    }

    public function canTransitionTo($activity)
    {
        return $this->process->canTransition($this->activityKey, $activity);
    }

    public function getActivity()
    {
        return $this->process->getActivity($this->activityKey);
    }

    public function __construct(Process $process, $activity = null)
    {
        $this->process = $process;

        if ($activity instanceof Activity) {
            $this->activityKey = $activity->getKey();
        } else {
            $this->activityKey = $activity;
        }
    }
}
