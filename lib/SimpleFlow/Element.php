<?php

namespace SimpleFlow;

/**
 * Top-level element.
 */
interface Element
{
    /**
     * Get human readable name.
     * @return string
     */
    public function getName();

    /**
     * Get machine name.
     * @return scalar
     */
    public function getKey();
}
