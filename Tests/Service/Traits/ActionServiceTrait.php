<?php

namespace Requestum\ApiGeneratorBundle\Tests\Service\Traits;

use Requestum\ApiGeneratorBundle\Exception\CollectionException;
use Requestum\ApiGeneratorBundle\Exception\EntityMissingException;
use Requestum\ApiGeneratorBundle\Exception\FormMissingException;
use Requestum\ApiGeneratorBundle\Helper\FileHelper;
use Requestum\ApiGeneratorBundle\Model\Action;
use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
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
     * @param string $nodeName
     * @param string $filePath
     *
     * @return Action[]|null
     *
     * @throws CollectionException
     * @throws EntityMissingException
     * @throws FormMissingException
     * @throws \Exception
     */
    protected function getActionNode(string $nodeName, string $filePath): ?array
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
        $collection = $builder->build(FileHelper::load($filePath), $entityCollection, $formCollection);

        return $collection->findElement($nodeName);
    }
}
