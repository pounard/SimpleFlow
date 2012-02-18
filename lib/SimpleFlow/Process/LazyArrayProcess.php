<?php

namespace SimpleFlow\Process;

use SimpleFlow\Activity\Activity;
use SimpleFlow\Activity\DefaultActivity;
use SimpleFlow\AbstractElement;
use SimpleFlow\ElementNotFoundException;

/**
 * Read-only process implementation based upon a definition array which lazy
 * loads any piece of data on demand when needed
 *
 * In order to load it correctly, an internal array format is defined therefore
 * allowing us to provide reader and writers interfaces, as well as a base
 * implementation for those, masking this internal format
 */
class LazyArrayProcess extends AbstractArrayProcess implements Process
{
    public function getActivity($key)
    {
        if (!isset($this->activities[$key])) {
            throw new ElementNotFoundException($key, $this);
        }

        if (!$this->activities[$key] instanceof Activity) {
            $this->activities[$key] = new DefaultActivity($key, $this->activities[$key]);
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

    /**
     * Default constructor
     * @param array $definition
     * @param bool $doCheck = true
     */
    public function __construct(array $definition, $doCheck = true)
    {
        /*
        $this->activities = $activities;
        $this->activitySparseMatrix = $activityMatrix;

        if ($doCheck) {
            foreach ($this->activitySparseMatrix as $from => $data) {

                if (!isset($this->activities[$from])) {
                    throw new \InvalidArgumentException(sprintf("Invalid activity matrix: %s does not exist", $from));
                }

                foreach ($data as $to => $event) {
                    if (!isset($this->activities[$to])) {
                        throw new \InvalidArgumentException(sprintf("Invalid activity matrix: %s does not exist", $to));
                    }
                }
            }
        }
         */

        throw new \RuntimeException("Not implemented");
    }
}
