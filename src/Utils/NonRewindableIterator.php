<?php

namespace SubstitutionPlugin\Utils;

final class NonRewindableIterator implements \Iterator
{
    private $values = array();
    private $key = 0;

    public function add($value)
    {
        if (!in_array($value, $this->values)) {
            $this->values[] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->values[$this->key];
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->key++;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return isset($this->values[$this->key]);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        // no rewind
    }
}
