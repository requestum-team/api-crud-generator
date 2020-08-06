<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Builder;

use PHPUnit\Framework\TestCase;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;
use Requestum\ApiGeneratorBundle\Service\Config;
use Requestum\ApiGeneratorBundle\Tests\TestCaseTrait;

/**
 * Class FormBuilderTest
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Builder
 */
class FormBuilderTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $configPath = realpath(__DIR__ .  '/../../../config.example.yml');
        $this->config = new Config($configPath);
    }

    /**
     * @dataProvider structureProvider
     */
    public function testStructure(string $filename)
    {
        $filePath = realpath(__DIR__ . '/providers/' . $filename);

        $entityBuilder = new EntityBuilder($this->config);
        $entityCollection = $entityBuilder->build(
            $this->getFileContent($filePath)
        );

        $formBuilder = new FormBuilder($this->config);
        $collection = $formBuilder->build(
            $this->getFileContent($filePath),
            $entityCollection
        );
    }

    public function structureProvider()
    {
        return [
            [
                'form-structure.yaml'
            ],
        ];
    }
}
