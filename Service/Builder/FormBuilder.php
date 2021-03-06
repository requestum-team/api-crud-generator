<?php

namespace Requestum\ApiGeneratorBundle\Service\Builder;

use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\CommonHelper;
use Requestum\ApiGeneratorBundle\Helper\FormHelper;
use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\FormCollection;
use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Class FormBuilder
 *
 * @package Requestum\ApiGeneratorBundle\Service\Builder
 */
class FormBuilder implements BuilderInterface
{

    /**
     * @var FormCollection
     */
    private $collection;

    /**
     * @param array $openApiSchema
     * @param BaseAbstractCollection|null $relatedCollection
     *
     * @return BaseAbstractCollection
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     */
    public function build(array $openApiSchema, ?BaseAbstractCollection $relatedCollection = null): BaseAbstractCollection
    {
        $this->collection = new FormCollection();

        if (empty($openApiSchema)) {
            return $this->collection;
        }

        foreach ($openApiSchema as $objectName => $objectData) {
            if (null !== ($name = FormHelper::getFormName($objectName))) {
                $required = !empty($objectData['required']) ? array_map(['\Requestum\ApiGeneratorBundle\Helper\StringHelper', 'camelCaseToSnakeCaseName'], $objectData['required']): [];
                $entity = null;
                if (!empty($objectData['x-entity']['$ref'])) {
                    $entityObjectName = StringHelper::getReferencedSchemaObjectName($objectData['x-entity']['$ref']);

                    $entityName = StringHelper::getEntityNameFromObjectName($entityObjectName);
                    $this->checkRelatedCollectionIsEmpty($name, $entityObjectName, $relatedCollection);
                    $entity = $relatedCollection->findElement($entityName);
                    if (is_null($entity)) {
                        throw new EntityMissingException(
                            sprintf(
                                'Entity %s is missing in the entity collection',
                                $entityName,
                            )
                        );
                    }
                }

                $form = new Form();
                $form
                    ->setName($name)
                    ->setDescription(!empty($objectData['description']) ? $objectData['description']: null)
                    ->setEntity($entity)
                    ->setProperties(
                        $this->buildProperties($objectData['properties'], $required, $entity)
                    )
                ;

                $this->collection->addElement($form);
            }
        }

        $this->processRelations($relatedCollection);

        return $this->collection;
    }

    /**
     * @param array  $propertiesData
     * @param array  $required
     * @param Entity $entity
     *
     * @return array
     *
     * @throws \Exception
     */
    private function buildProperties(array $propertiesData, array $required, ?Entity $entity = null): array
    {
        $properties = [];

        foreach ($propertiesData as $field => $data) {
            $property = new FormProperty();
            $property
                ->setNameCamelCase(
                    StringHelper::snakeCaseToCamelCaseName($field)
                )
                ->setNameSnakeCase(
                    StringHelper::camelCaseToSnakeCaseName($field)
                )
                ->setDescription(!empty($data['description']) ? $data['description']: null)
                ->setRequired(in_array($property->getNameSnakeCase(), $required))
            ;

            if (!empty($data['type'])) {
                $property->setType($data['type']);
            }

            if (!empty($data['format'])) {
                $property->setFormat($data['format']);
            }

            if (!empty($data['enum']) && is_array($data['enum'])) {
                $property->setEnum(
                    array_map(['\Requestum\ApiGeneratorBundle\Helper\StringHelper', 'camelCaseToSnakeCaseName'], $data['enum'])
                );
            }

            if (!empty($data['minLength'])) {
                $property->getLength()->setMin($data['minLength']);
            }

            if (!empty($data['maxLength'])) {
                $property->getLength()->setMax($data['maxLength']);
            }

            if (!empty($data['$ref'])) {
                $property->setReferencedLink(
                    StringHelper::getReferencedSchemaObjectName($data['$ref'])
                );
            }

            if (!empty($data['items']['$ref'])) {
                $property->setReferencedLink(
                    StringHelper::getReferencedSchemaObjectName($data['items']['$ref'])
                );
            }

            $properties[] = $property;
        }

        return $properties;
    }

    /**
     * @param BaseAbstractCollection|null $relatedCollection
     *
     * @return $this
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     */
    private function processRelations(?BaseAbstractCollection $relatedCollection = null): self
    {
        /**
         * @var string $formName
         * @var Form   $form
         */
        foreach ($this->collection->getElements() as $formName => $form) {
            /** @var FormProperty $property */
            foreach ($form->filterReferencedLinkProperties() as $property) {
                if (is_null($property->getReferencedLink())) {
                    continue;
                }

                if (CommonHelper::isEntity($property->getReferencedLink())) {
                    $this->checkRelatedCollectionIsEmpty($formName, $property->getReferencedLink(), $relatedCollection);
                    $entityName = StringHelper::getEntityNameFromObjectName($property->getReferencedLink());
                    $referencedObject = $relatedCollection->findElement($entityName);
                    if (is_null($referencedObject)) {
                        throw new EntityMissingException(
                            sprintf(
                                'Entity %s is missing in the entity collection',
                                $entityName,
                            )
                        );
                    }

                    $property
                        ->setIsEntity(true)
                        ->setReferencedObject($referencedObject)
                    ;
                }

                if (CommonHelper::isForm($property->getReferencedLink())) {
                    $referencedObject = $this->collection->findElement($property->getReferencedLink());
                    if (is_null($referencedObject)) {
                        throw new FormMissingException(
                            sprintf(
                                'Form %s has a relation with missing form %s',
                                $formName,
                                $property->getReferencedLink()
                            )
                        );
                    }
                    $property
                        ->setIsForm(true)
                        ->setReferencedObject($referencedObject)
                    ;
                }
            }
        }

        return $this;
    }

    /**
     * @param string $formName
     * @param string $entityObjectName
     * @param BaseAbstractCollection|null $relatedCollection
     *
     * @throws CollectionException
     */
    private function checkRelatedCollectionIsEmpty(string $formName, string $entityObjectName, ?BaseAbstractCollection $relatedCollection = null)
    {
        if (is_null($relatedCollection)
            || (
                ($relatedCollection instanceof BaseAbstractCollection)
                && $relatedCollection->isEmpty())
            )
        {
            throw new CollectionException(
                sprintf(
                    'Required the entity collection. Form %s has as a dependency an entity %s',
                    $formName,
                    $entityObjectName
                )
            );
        }
    }
}
