<?php

namespace Requestum\ApiGeneratorBundle\Helper;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FileHelper
 * @package Requestum\ApiGeneratorBundle\Helper
 */
class FileHelper
{
    const SUPPORTED_EXT = ['json', 'yml', 'yaml'];

    /**
     * @param string $filePath
     *
     * @return array|null
     *
     * @throws \Exception
     */
    public static function load(string $filePath): ?array
    {
        $fs = new Filesystem();
        if (!$fs->exists($filePath)) {
            throw new FileNotFoundException(null, 0, null, $filePath);
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (!in_array($ext, self::SUPPORTED_EXT)) {
            throw new \Exception('Unsupported format of file');
        }

        $content = [];

        switch ($ext) {
            case 'json':
                $content = json_decode(file_get_contents($filePath), true);
                break;
            case 'yml':
            case 'yaml':
            $content =  Yaml::parseFile($filePath);
                break;
        }

        return $content;
    }
}
