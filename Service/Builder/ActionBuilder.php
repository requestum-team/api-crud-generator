<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Exception\ActionClassDefineException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\ActionHelper;
use Requestum\ApiGeneratorBundle\Helper\CommonHelper;
use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\ActionCollection;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Model\BaseModel;

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
     * @param BaseAbstractCollection|null $relatedEntityCollection
     * @param BaseAbstractCollection|null $relatedFormCollection
     *
     * @return BaseAbstractCollection
     *
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     */
    public function build(
        array $openApiSchema,
        ?BaseAbstractCollection $relatedEntityCollection = null,
        ?BaseAbstractCollection $relatedFormCollection = null
    ): BaseAbstractCollection
    {
        if (empty($openApiSchema[self::SCHEMA_KEY])) {
            return $this->collection;
        }

        foreach ($openApiSchema[self::SCHEMA_KEY] as $path => $methodsData) {
            foreach ($methodsData as $method => $data) {
                if (!empty($data['x-skip']) && $data['x-skip'] === true) {
                    continue;
                }

                $operationId = !empty($data['operationId']) ? $data['operationId'] : null;

                if (empty($actionClass = ActionHelper::getActionClassByMethod($method, $operationId))) {
                    throw new ActionClassDefineException($path, $method, $operationId);
                }

                if (empty($entityClassName = $this->extractEntityClassName($data, $openApiSchema))
                    || empty($entity = $relatedEntityCollection->findElement($entityClassName))
                ) {
                    throw new EntityMissingException('Cannot define entity class for action.');
                }

                $name = strtolower($entityClassName);

                $action = (new Action())
                    ->setName($name)
                    ->setMethod($method)
                    ->setClassName($actionClass)
                    ->setEntity($entity)
                    ->setPath($path)
                    ->setServicePath([
                        $name,
                    ])
                ;

                if (in_array($method, [
                        BaseModel::ALLOWED_METHOD_POST,
                        BaseModel::ALLOWED_METHOD_PATCH,
                        BaseModel::ALLOWED_METHOD_PUT
                    ])
                ) {
                    if (empty($formClassName = $this->extractFormClassName($data, $openApiSchema))
                        || empty($form = $relatedFormCollection->findElement($formClassName))
                    ) {
                        throw new FormMissingException('Cannot define form class for action.');
                    }

                    $action->setForm($form);
                }

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

    /**
     * @param array $data
     * @param array $openApiSchema
     *
     * @return string|null
     */
    private function extractFormClassName(array $data, array &$openApiSchema): ?string
    {
        $requestBodyRef = CommonHelper::getArrayValueByPath($data, ['requestBody', '$ref',]);

        return StringHelper::getReferencedSchemaObjectName($requestBodyRef);
    }
}
