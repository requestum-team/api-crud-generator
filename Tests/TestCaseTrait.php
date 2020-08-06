<?php

namespace Requestum\ApiGeneratorBundle\Tests;

use Requestum\ApiGeneratorBundle\Helper\FileHelper;

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
            \sprintf('Cannot open file "%s".' . "\n", $filename)
        );
    }
}
