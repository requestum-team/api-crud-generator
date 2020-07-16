<?php

namespace Requestum\ApiGeneratorBundle\Generators;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\DocBlockGenerator;

/**
 * Class BundleGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Generators
 */
class BundleGenerator
{
    /**
     * @param string $bundleName
     *
     * @return string
     */
    public static function generate(string $bundleName): string
    {
        $class    = new ClassGenerator();
        $docblock = DocBlockGenerator::fromArray([
            'shortDescription' => 'Sample generated class',
            'longDescription'  => 'This is a class generated with Zend\Code\Generator.',
            'tags'             => [
                [
                    'name'        => 'version',
                    'description' => '$Rev:$',
                ],
                [
                    'name'        => 'license',
                    'description' => 'New BSD',
                ],
            ],
        ]);
        $class
            ->setName($bundleName)
            ->setExtendedClass('Bundle')
            ->setDocblock($docblock)
        ;

        $file = new FileGenerator([
            'namespace' => $bundleName,
            'classes' => [$class],
            'uses' => [
                'Symfony\Component\HttpKernel\Bundle\Bundle'
            ],
        ]);

        return $file->generate();
    }
}
