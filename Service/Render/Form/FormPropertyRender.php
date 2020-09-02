<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form;

use Requestum\ApiGeneratorBundle\Model\FormProperty;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\ArrayFormPropertyType;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\EmailFormPropertyType;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\EntityFormPropertyType;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\EnumStringFormPropertyType;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\FormFormPropertyType;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\FormPropertyTypeInterface;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\IntegerFormPropertyType;
use Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType\StringFormPropertyType;

/**
 * Class FormPropertyRender
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render
 */
class FormPropertyRender
{
    /** @var string[] */
    protected array $renders = [
        StringFormPropertyType::class,
        EmailFormPropertyType::class,
        EnumStringFormPropertyType::class,
        IntegerFormPropertyType::class,
        EntityFormPropertyType::class,
        ArrayFormPropertyType::class,
        FormFormPropertyType::class,
    ];

    /** @var FormPropertyTypeInterface */
    protected FormPropertyTypeInterface $render;

    /** @var string */
    protected string $bundleName;

    /**
     * FormPropertyRender constructor.
     *
     * @param string $bundleName
     */
    public function __construct(string $bundleName)
    {
        $this->bundleName = $bundleName;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyTypeInterface
     */
    protected function getSupportedRender(FormProperty $formProperty): FormPropertyTypeInterface
    {
        foreach ($this->renders as $render) {
            if ($render::isSupport($formProperty)) {
                return new $render($this->bundleName);
            }
        }

        throw new \LogicException(
            sprintf("Form property type '%s' cannot get supported render", $formProperty->getType())
        );
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return FormPropertyRenderOutput
     */
    public function render(FormProperty $formProperty): FormPropertyRenderOutput
    {
        return $this
            ->getSupportedRender($formProperty)
            ->render($formProperty)
        ;
    }
}
