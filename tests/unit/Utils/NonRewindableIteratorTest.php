<?php

namespace SubstitutionPlugin\Utils;

use SubstitutionPlugin\BaseUnitTestCase;

class NonRewindableIteratorTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public function testEmptyIterator()
    {
        $it = new NonRewindableIterator();
        foreach ($it as $k => $v) {
            throw new \LogicException('Should not enter the loop');
        }

        /** @phpstan-ignore-next-line */
        self::assertFalse(isset($k, $v));
    }

    /**
     * @return void
     */
    public function testSimpleIteration()
    {
        $it = new NonRewindableIterator();
        $it->add('a');
        $it->add('b');
        $it->add('c');

        $keys = array();
        $values = array();

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        self::assertEquals(array(0, 1, 2), $keys);
        self::assertEquals(array('a', 'b', 'c'), $values);
    }

    /**
     * @return void
     */
    public function testSimpleIterationWithDuplicates()
    {
        $it = new NonRewindableIterator();
        $it->add('a');
        $it->add('b');
        $it->add('b');
        $it->add('a');
        $it->add('c');

        $keys = array();
        $values = array();

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        self::assertEquals(array(0, 1, 2), $keys);
        self::assertEquals(array('a', 'b', 'c'), $values);
    }

    /**
     * @return void
     */
    public function testAddAll()
    {
        $it = new NonRewindableIterator();
        $it->addAll(array('a', 'b', 'b', 'a', 'c'));

        $keys = array();
        $values = array();

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        self::assertEquals(array(0, 1, 2), $keys);
        self::assertEquals(array('a', 'b', 'c'), $values);
    }

    /**
     * @return void
     */
    public function testIterationWithModification()
    {
        $it = new NonRewindableIterator();
        $it->add('a');
        $it->add('b');
        $it->add('c');

        $keys = array();
        $values = array();

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;

            if ($k === 1) {
                $it->add('d');
            }
        }

        self::assertEquals(array(0, 1, 2, 3), $keys);
        self::assertEquals(array('a', 'b', 'c', 'd'), $values);
    }

    /**
     * @return void
     */
    public function testIteratorIsNonRewindable()
    {
        $it = new NonRewindableIterator();
        $it->add('a');
        $it->add('b');
        $it->add('c');

        $keys = array();
        $values = array();

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        // another loop
        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        self::assertEquals(array(0, 1, 2), $keys);
        self::assertEquals(array('a', 'b', 'c'), $values);
    }

    /**
     * @return void
     */
    public function testAddBetweenIterations()
    {
        $it = new NonRewindableIterator();
        $it->add('a');
        $it->add('b');

        $keys = array();
        $values = array();

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        self::assertEquals(array(0, 1), $keys);
        self::assertEquals(array('a', 'b'), $values);

        $it->add('b');
        $it->add('c');

        foreach ($it as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
        }

        self::assertEquals(array(0, 1, 2), $keys);
        self::assertEquals(array('a', 'b', 'c'), $values);
    }
}
