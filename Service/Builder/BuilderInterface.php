<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Service\Config;

/**
 * Class BuilderInterface
 *
 * @package Requestum\ApiGeneratorBundle\Service\Builder
 */
interface BuilderInterface
{
    /**
     * @param array $openApiSchema
     *
     * @return $collection
     */
    public function build(array $openApiSchema, ?BaseAbstractCollection $relatedCollection = null): BaseAbstractCollection;
}
