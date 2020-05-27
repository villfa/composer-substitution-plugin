<?php

namespace SubstitutionPlugin\Transformer;

use DummyTransformer;
use SubstitutionPlugin\BaseUnitTestCase;

class TransformerCollectionTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        require_once self::getFixturesDir() . '/DummyTransformer.php';
    }

    /**
     * @return void
     */
    public function testEmptyCollection()
    {
        $transformer = new TransformerCollection();
        self::assertEquals('foo', $transformer->transform('foo'));
    }

    /**
     * @return void
     */
    public function testWithOneTransformer()
    {
        $innerTransformer = new DummyTransformer('bar');
        $transformer = new TransformerCollection(array($innerTransformer));
        self::assertEquals('bar', $transformer->transform('foo'));
        self::assertEquals(1, $innerTransformer->getCount());
    }

    /**
     * @return void
     */
    public function testAddTransformer()
    {
        $innerTransformer = new DummyTransformer('bar');
        $transformer = new TransformerCollection();
        $transformer->addTransformer($innerTransformer);
        self::assertEquals('bar', $transformer->transform('foo'));
        self::assertEquals(1, $innerTransformer->getCount());
    }

    /**
     * @return void
     */
    public function testWithSeveralTransformers()
    {
        $innerTransformer01 = new DummyTransformer('bar');
        $innerTransformer02 = new DummyTransformer('baz');
        $transformer = new TransformerCollection(array($innerTransformer01, $innerTransformer02));
        self::assertEquals('baz', $transformer->transform('foo'));
        self::assertEquals(1, $innerTransformer01->getCount());
        self::assertEquals(1, $innerTransformer02->getCount());
    }

    /**
     * @return void
     */
    public function testWithSeveralTransformersAndAddTransformer()
    {
        $innerTransformer01 = new DummyTransformer('bar');
        $innerTransformer02 = new DummyTransformer('baz');
        $innerTransformer03 = new DummyTransformer('qux');
        $innerTransformer04 = new DummyTransformer('quux');
        $transformer = new TransformerCollection(array($innerTransformer01, $innerTransformer02));
        $transformer->addTransformer($innerTransformer03);
        $transformer->addTransformer($innerTransformer04);
        self::assertEquals('quux', $transformer->transform('foo'));
        self::assertEquals(1, $innerTransformer01->getCount());
        self::assertEquals(1, $innerTransformer02->getCount());
        self::assertEquals(1, $innerTransformer03->getCount());
        self::assertEquals(1, $innerTransformer04->getCount());
    }
}
