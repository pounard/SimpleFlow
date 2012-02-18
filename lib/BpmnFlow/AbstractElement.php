<?php

namespace BpmnFlow;

abstract class AbstractElement implements Element
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var scalar
     */
    protected $key;

    public function getName()
    {
        return $this->name;
    }

    public function getKey()
    {
        return $this->key;
    }

    /**
     * Default constructo
     * @param scalar $key
     * @param string $name If not set, the key casted as string will be used
     */
    public function __construct($key, $name = null)
    {
        $this->key = $key;

        if (isset($name)) {
            $this->name = $name;
        } else {
            $this->name = (string)$key;
        }
    }
}
