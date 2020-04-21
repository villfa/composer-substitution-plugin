<?php

namespace SubstitutionPlugin\Utils;

final class NonRewindableIterator implements \Iterator
{
    /** @var array<string, int> */
    private $values = array();

    /** @var string|null */
    private $value = null;

    /** @var int */
    private $total = 0;

    /**
     * @param string $value
     * @return void
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
     * @param string[] $values
     * @return void
     */
    public function addAll(array $values)
    {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    /**
     * @return string|null
     */
    public function current()
    {
        return $this->value;
    }

    /**
     * @return void
     */
    public function next()
    {
        next($this->values);
        $this->value = key($this->values);
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->values[$this->value];
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->value !== null;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        // no rewind
    }
}
