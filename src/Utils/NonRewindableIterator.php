<?php

namespace SubstitutionPlugin\Utils;

final class NonRewindableIterator implements \Iterator
{
    private $values = array();
    private $value = null;
    private $total = 0;

    /**
     * @param string $value
     */
    public function add($value)
    {
        if (!isset($this->values[$value])) {
            if ($this->value === null) {
                $this->value = $value;
            }
            $this->values[$value] = $this->total;
            $this->total++;
        }
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        next($this->values);
        $this->value = key($this->values);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->values[$this->value];
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->value !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        // no rewind
    }
}
