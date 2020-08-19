<?php

namespace Requestum\ApiGeneratorBundle\Tests;

use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Service\InheritanceHandler;

/**
 * Trait TestCaseTrait
 *
 * @package Requestum\ApiGeneratorBundle\Tests
 */
trait TestCaseTrait
{
    /**
     * @param string $filename
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getFileContent(string $filePath): array
    {
        if (file_exists($filePath)) {
            return FileHelper::load($filePath);
        }

        throw new \Exception(
            \sprintf('Cannot open file "%s".' . "\n", $filePath)
        );
    }

    /**
     * @param string $filePath
     * @return array
     *
     * @throws \Exception
     */
    private function getSchemasAndRequestBodiesCollection(string $filePath)
    {
        $inheritanceHandler = new InheritanceHandler();

        return $inheritanceHandler->process(
            $this->getFileContent($filePath)
        );
    }
}
