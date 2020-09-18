<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Exception\ActionClassDefineException;
use Requestum\ApiGeneratorBundle\Helper\ActionHelper;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\ActionCollection;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;

/**
 * Class ActionBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Builder
 */
class ActionBuilder implements BuilderInterface
{
    /** @var string */
    const SCHEMA_KEY = 'paths';

    /** @var ActionCollection */
    private ActionCollection $collection;

    /**
     * ActionBuilder constructor.
     */
    public function __construct()
    {
        $this->collection = new ActionCollection();
    }

    /**
     * @param array $openApiSchema
     * @param BaseAbstractCollection|null $relatedCollection
     *
     * @return BaseAbstractCollection
     */
    public function build(
        array $openApiSchema,
        ?BaseAbstractCollection $relatedCollection = null
    ): BaseAbstractCollection
    {
        if (empty($openApiSchema[self::SCHEMA_KEY])) {
            return $this->collection;
        }

        foreach ($openApiSchema[self::SCHEMA_KEY] as $path => $methodsData) {
            foreach ($methodsData as $method => $data) {
                $operationId = !empty($data['operationId']) ? $data['operationId'] : null;

                if (is_null($actionClassName = ActionHelper::getActionClassByMethod($method, $operationId))) {
                    throw new ActionClassDefineException($path, $method, $operationId);
                }

                $action = (new Action())
                    ->setMethod($method)
                    ->setClassName($actionClassName)
                    ->setArguments([])
                ;

                $this->collection->addElement($action);
            }
        }

        return $this->collection;
    }
}
