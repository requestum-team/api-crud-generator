<?php

namespace Requestum\ApiGeneratorBundle\Helper;

use Requestum\ApiGeneratorBundle\Model\Entity;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;

/**
 * Class FormHelper
 *
 * @package Requestum\ApiGeneratorBundle\Helper
 */
class FormHelper
{
    /**
     * @param string $objectName
     *
     * @return string|null
     */
    public static function getFormName(string $objectName): ?string
    {
        return CommonHelper::isForm($objectName) ? $objectName: null;
    }

    /**
     * @param Entity $entity
     *
     * @return string
     */
    public static function getFormClassNameByEntity(Entity $entity): string
    {
        return $entity->getName() . FormGeneratorModelBuilder::NAME_POSTFIX;
    }

    /**
     * @param string $bundleName
     * @param Form $form
     *
     * @return string
     */
    public static function getFormNameSpace(string $bundleName, Form $form): string
    {
        return sprintf(
            '%s\%s\%s',
            $bundleName,
            $form->getNameSpace(),
            self::getFormClassNameByEntity($form->getEntity())
        );
    }
}
