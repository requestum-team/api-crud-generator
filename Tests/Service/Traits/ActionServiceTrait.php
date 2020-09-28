<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Traits;

use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\BaseAbstractCollection;
use Requestum\ApiGeneratorBundle\Service\Builder\ActionBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\EntityBuilder;
use Requestum\ApiGeneratorBundle\Service\Builder\FormBuilder;

/**
 * Trait ActionServiceTrait
 *
 * @package Requestum\ApiGeneratorBundle\Tests\Service\Traits
 */
trait ActionServiceTrait
{
    /**
     * @param string $filePath
     *
     * @return BaseAbstractCollection
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     */
    protected function getActionCollection(string $filePath)
    {
        $data = $this->getSchemasAndRequestBodiesCollection($filePath);

        $entityBuilder = new EntityBuilder();
        $entityCollection = $entityBuilder->build(
            $data
        );

        $formBuilder = new FormBuilder();
        $formCollection = $formBuilder->build(
            $data,
            $entityCollection
        );

        $builder = new ActionBuilder();

        return $builder->build(FileHelper::load($filePath), $entityCollection, $formCollection);
    }

    /**
     * @param string $nodeName
     * @param BaseAbstractCollection $collection
     *
     * @return Action[]|null
     *
     */
    protected function getActionNode(string $nodeName, BaseAbstractCollection $collection): ?array
    {
        return $collection->findElement($nodeName);
    }
}
