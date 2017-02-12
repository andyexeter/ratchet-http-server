<?php

namespace Palmtree\Service\Config;

/**
 * Class Config
 * @package    Palmtree\Service
 * @subpackage Config
 */

/**
 * Class Config
 * @package Palmtree\Service\Config
 */
class Config implements \ArrayAccess, \Serializable
{
    /**
     * @var array
     */
    private $data = [];

    private $deepCache = [];

    /**
     * Config constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function all()
    {
        return $this->data;
    }

    public function merge($parameters)
    {
        $this->data = array_replace_recursive($this->data, $parameters);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]) || array_key_exists($key, $this->data);
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        // Allow nested config values to be retreived e.g paths.app
        if (strpos($key, '.') !== false) {
            if (! array_key_exists($key, $this->deepCache)) {
                $tmp = $this->data;

                foreach (explode('.', $key) as $part) {
                    if (! array_key_exists($part, $tmp)) {
                        unset($this->deepCache[$key]);

                        return null;
                    }

                    $tmp = $tmp[$part];
                }

                $this->deepCache[$key] = $tmp;
            }

            return $this->deepCache[$key];
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        return unserialize($serialized);
    }
}
