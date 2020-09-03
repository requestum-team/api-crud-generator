<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Generator\FormGeneratorModelBuilder;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class FormFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class FormFormPropertyType extends FormPropertyTypeAbstract
{
    /**
     * @param FormProperty $formProperty
     *
     * @return bool
     */
    public static function isSupport(FormProperty $formProperty): bool
    {
        return
            $formProperty->isForm()
            && empty($formProperty->getType())
        ;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        /** @var Form $form */
        $form = $formProperty->getReferencedObject();
        $entity = $form->getEntity();
        $subFormClass = $entity->getName() . FormGeneratorModelBuilder::NAME_POSTFIX;
        $subFormNameSpace = sprintf('%s\%s\%s', $this->bundleName, $form->getNameSpace(), $subFormClass);

        // todo FormFormPropertyType
        return (new FormPropertyRenderOutput())
            ->setUseSections([
                $subFormNameSpace,
            ])
            ->setContent($this->wrapProperty(
                $formProperty->getNameCamelCase(),
                $subFormClass,
                $this->getNeededConstraints($formProperty)
            ))
        ;
    }
}
