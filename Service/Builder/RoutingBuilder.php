<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Model\Routing;
use Requestum\ApiGeneratorBundle\Model\RoutingCollection;

/**
 * Class RoutingBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Builder
 */
class RoutingBuilder implements BuilderInterface
{
    /** @var string */
    const SCHEMA_KEY = 'paths';

    /** @var RoutingCollection */
    private RoutingCollection $collection;

    public function __construct()
    {
        $this->collection = new RoutingCollection();
    }

    /**
     * @param array $openApiSchema
     * @param BaseAbstractCollection|null $relatedActionCollection
     *
     * @return BaseAbstractCollection
     *
     * @throws \Exception
     */
    public function build(
        array $openApiSchema,
        ?BaseAbstractCollection $relatedActionCollection = null
    ): BaseAbstractCollection
    {
        /* $openApiSchema required by interface. Maybe this array will be needed in the future. */
        if (empty($openApiSchema[self::SCHEMA_KEY])
            || empty($relatedActionCollection)
        ) {
            return $this->collection;
        }

        foreach ($relatedActionCollection->getElements() as $actionNode => $actions) {
            foreach ($actions as $action) {
                /** @var Action $action */
                $routing = new Routing($action);

                $this->collection->addElement($routing);
            }
        }

        return $this->collection;
    }
}
