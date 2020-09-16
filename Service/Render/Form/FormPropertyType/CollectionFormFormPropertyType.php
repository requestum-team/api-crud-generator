<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Helper\FormHelper;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Model\Form;
use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyRenderOutput;

/**
 * Class CollectionFormFormPropertyType
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
class CollectionFormFormPropertyType extends FormPropertyTypeAbstract
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
            && $formProperty->getType() === PropertyTypeEnum::TYPE_ARRAY
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
        $subFormClass = FormHelper::getFormClassNameByEntity($form->getEntity());

        $formPropertyConstraintDto = $this->getNeededConstraints($formProperty);

        $optionsContent = <<<EOF
    'entry_type' => {$subFormClass}::class,
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
EOF;

        return (new FormPropertyRenderOutput())
            ->addUseSections([
                'Symfony\Component\Form\Extension\Core\Type\CollectionType',
                FormHelper::getFormNameSpace($this->bundleName, $form),
            ])
            ->addUseSections($formPropertyConstraintDto->getUses())
            ->setContent(
                $this->wrapProperty(
                    $formProperty->getNameCamelCase(),
                    'CollectionType',
                    $formPropertyConstraintDto->getContents(),
                    $optionsContent
                )
            )
        ;
    }
}
