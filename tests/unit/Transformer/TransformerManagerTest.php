<?php

namespace SubstitutionPlugin\Transformer;

use DummyTransformer;
use DummyTransformerFactory;
use Psr\Log\NullLogger;
use SubstitutionPlugin\BaseUnitTestCase;
use SubstitutionPlugin\Config\PluginConfiguration;

class TransformerManagerTest extends BaseUnitTestCase
{
    protected function doSetUp()
    {
        parent::doSetUp();
        require_once self::getFixturesDir() . '/DummyTransformer.php';
        require_once self::getFixturesDir() . '/DummyTransformerFactory.php';
    }

    private function buildTransformerManager(TransformerInterface $transformer)
    {
        $logger = new NullLogger();
        $config = new PluginConfiguration(array(), $logger);
        $transformerFactory = new DummyTransformerFactory($transformer);
        return new TransformerManager($transformerFactory, $config, $logger);
    }

    public function testApplySubstitutionsChangesNothingWithoutNames()
    {
        $scripts = $expectedScripts = array(
            'test' => array(
                'echo TEST',
            ),
        );
        $scriptNames = array();
        $transformerManager = $this->buildTransformerManager(new DummyTransformer('foo'));
        $modifiedScripts = $transformerManager->applySubstitutions($scripts, $scriptNames);

        self::assertEquals($expectedScripts, $modifiedScripts);
    }

    public function testApplySubstitutionsChangesNothingWithUnknownScript()
    {
        $scripts = $expectedScripts = array(
            'test' => array(
                'echo TEST',
            ),
        );
        $scriptNames = array('foo');
        $transformerManager = $this->buildTransformerManager(new DummyTransformer('foo'));
        $modifiedScripts = $transformerManager->applySubstitutions($scripts, $scriptNames);

        self::assertEquals($expectedScripts, $modifiedScripts);
    }

    public function testApplySubstitutionsChangesOneScript()
    {
        $scripts = array(
            'test' => array(
                'echo TEST',
            ),
        );
        $expectedScripts = array(
            'test' => array(
                'foo',
            ),
        );
        $scriptNames = array('test');
        $transformerManager = $this->buildTransformerManager(new DummyTransformer('foo'));
        $modifiedScripts = $transformerManager->applySubstitutions($scripts, $scriptNames);

        self::assertEquals($expectedScripts, $modifiedScripts);
    }

    public function testApplySubstitutionsWithRecursion()
    {
        $scripts = array(
            'test' => array(
                '@subtest',
            ),
            'subtest' => array(
                'echo TEST',
            ),
            'untouched' => array(
                'untouched',
            ),
        );
        $expectedScripts = array(
            'test' => array(
                '@subtest',
            ),
            'subtest' => array(
                'echo FOOBAR',
            ),
            'untouched' => array(
                'untouched',
            ),
        );
        $scriptNames = array('test');
        $transformerManager = $this->buildTransformerManager(new DummyTransformer(function ($value) {
            return str_replace('TEST', 'FOOBAR', $value);
        }));
        $modifiedScripts = $transformerManager->applySubstitutions($scripts, $scriptNames);

        self::assertEquals($expectedScripts, $modifiedScripts);
    }
}
