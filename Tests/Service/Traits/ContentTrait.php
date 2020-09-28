<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Traits;

/**
 * Trait ContentTrait
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Traits
 */
trait ContentTrait
{
    /**
     * @param string $content
     */
    protected function minimizeContent(string &$content)
    {
        $replace = ["/[\n\r\s]+/u", ' '];
        $content = preg_replace($replace[0], $replace[1], $content);
    }
}
