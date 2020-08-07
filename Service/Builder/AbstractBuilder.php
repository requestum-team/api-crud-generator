<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Service\Config;

/**
 * Class AbstractBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Builder
 */
abstract class AbstractBuilder
{
    /**
     * @var BaseAbstractCollection
     */
    protected BaseAbstractCollection $collection;

    /**
     * @var Config
     */
    protected $config;

    /**
     * AbstractBuilder constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $openApiSchema
     *
     * @return $collection
     */
    public abstract function build(array $openApiSchema, ?BaseAbstractCollection $relatedCollection = null): BaseAbstractCollection;
}
