<?php

namespace ArtisanIo\Delimited;

use ArtisanIo\TestCase;
use Mockery as m;

abstract class BaseTest extends TestCase
{
    protected $emptyFile;
    protected $importFile;
    protected $fieldFile;
    protected $rulesFile;

    public function setUp(): void
    {
        parent::setUp();

        $this->emptyFile = __DIR__.'/empty.file';
        $this->importFile = __DIR__.'/import.csv';
        $this->fieldFile = __DIR__.'/import.fields';
        $this->rulesFile = __DIR__.'/import.rules';
    }

    public function tearDown(): void
    {
        parent::tearDown();

        m::close();

        // Check for "remnants"
        foreach ([$this->emptyFile, $this->importFile, $this->fieldFile, $this->rulesFile] as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }

    public function testItCanBeInstantiated()
    {
        $import = $this->getInstance();
        $this->assertInstanceOf($this->abstract, $import);
    }

    /**
     * Helper method.
     *
     * @param array $mocks
     *
     * @return EloquentTableImport
     */
    protected function getInstance($mocks = [])
    {
        $container = isset($mocks['container']) ?
            $mocks['container'] : m::mock('Illuminate\Contracts\Container\Container');
        $db = isset($mocks['db']) ?
            $mocks['db'] : m::mock('Illuminate\Database\DatabaseManager');
        $config = isset($mocks['config']) ?
            $mocks['config'] : m::mock('Illuminate\Config\Repository');
        $validator = isset($mocks['validator']) ?
            $mocks['validator'] : m::mock('Illuminate\Validation\Factory');

        $instance = new $this->abstract($container, $db, $config, $validator);

        return $instance;
    }

    /**
     * Helper method.
     *
     * @param $expect
     * @param $actual
     */
    protected function assertReturnsSelf($expect, $actual)
    {
        $this->assertInstanceOf(get_class($expect), $actual);
    }
}
