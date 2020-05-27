<?php

namespace SubstitutionPlugin\Transformer;

use SubstitutionPlugin\BaseUnitTestCase;

class NullTransformerTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public function testTransform()
    {
        $value = (string) rand(0, 10000);
        $transformer = new NullTransformer();
        self::assertEquals($value, $transformer->transform($value));
    }
}
