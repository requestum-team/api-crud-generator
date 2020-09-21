<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Exception\ActionClassDefineException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Helper\ActionHelper;
use Requestum\ApiGeneratorBundle\Helper\CommonHelper;
use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\ActionCollection;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Service\InheritanceHandler;

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
     * @throws EntityMissingException
     */
    public function build(
        array $openApiSchema,
        ?BaseAbstractCollection $relatedCollection = null
    ): BaseAbstractCollection
    {
        if (empty($openApiSchema[self::SCHEMA_KEY])) {
            return $this->collection;
        }

        //$inheritanceHandler = new InheritanceHandler();
        //$openApiSchemaCollection = $inheritanceHandler->process($openApiSchema);

        foreach ($openApiSchema[self::SCHEMA_KEY] as $path => $methodsData) {
            foreach ($methodsData as $method => $data) {
                $operationId = !empty($data['operationId']) ? $data['operationId'] : null;

                if (empty($actionClass = ActionHelper::getActionClassByMethod($method, $operationId))) {
                    throw new ActionClassDefineException($path, $method, $operationId);
                }

                if (empty($entityClassName = $this->extractEntityClassName($data, $openApiSchema))) {
                    throw new EntityMissingException('Cannot define entity class for action.');
                }

                $action = (new Action())
                    ->setName('test') /** todo */
                    ->setMethod($method)
                    ->setClassName($actionClass)
                    ->setEntityClassName($entityClassName)
                    /** todo */
                    //->setFormClassName(...)
                ;

                $this->collection->addElement($action);
            }
        }

        return $this->collection;
    }

    /**
     * @param array $data
     * @param array $openApiSchema
     *
     * @return string|null
     */
    private function extractEntityClassName(array $data, array &$openApiSchema): ?string
    {
        $entityClassName = null;
        $entityRef = CommonHelper::getArrayValueByPath($data, ['x-entity', '$ref',]);

        if (empty($entityRef)
            && !empty($requestBodyRef = CommonHelper::getArrayValueByPath($data, ['requestBody', '$ref',]))
        ) {
            $requestSchema = CommonHelper::getComponentsSchemaDataByPath(
                $openApiSchema,
                $requestBodyRef
            );

            if (!empty($requestSchema)) {
                $entityRef = CommonHelper::getArrayValueByPath(
                    $requestSchema,
                    ['content', 'application/json', 'schema', 'x-entity', '$ref',]
                );
            }
        }

        if (!empty($entityRef)) {
            $entityObjectName = StringHelper::getReferencedSchemaObjectName($entityRef);
            $entityClassName = StringHelper::getEntityNameFromObjectName($entityObjectName);
        }

        return $entityClassName;
    }
}
