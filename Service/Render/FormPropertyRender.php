<?php

namespace Requestum\ApiGeneratorBundle\Service\Render;

use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Class FormPropertyRender
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render
 */
class FormPropertyRender
{
    /** @var string */
    protected string $bundleName;

    /** @var FormProperty */
    protected FormProperty $formProperty;

    /** @var array */
    protected array $useSections = [];

    /**
     * FormPropertyRender constructor.
     *
     * @param string $bundleName
     * @param FormProperty $formProperty
     */
    public function __construct(string $bundleName, FormProperty $formProperty)
    {
        $this->bundleName = $bundleName;
        $this->formProperty = $formProperty;
    }

    /**
     * @return array
     */
    public function getUseSections(): array
    {
        return $this->useSections;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $type = $this->formProperty->getType();

        if (empty($type)) {
            if ($this->formProperty->isEntity()) {
                $type = 'entity';
            } elseif ($this->formProperty->isForm()) {
                $type = 'form';
            }
        }

        $method = 'renderType' . ucfirst($type);

        if (!method_exists($this, $method)) {
            throw new \LogicException(
                sprintf("Form property of type '%s' cannot be render", $this->formProperty->getType())
            );
        }

        return $this->{$method}();
    }

    /**
     * @param string $name
     * @param string $typeClass
     * @param string|null $options
     *
     * @return string
     */
    protected function getPropertyWrapper(string $name, string $typeClass, string $options = null): string
    {
        $content = <<<EOF
\n    ->add('{$name}', {$typeClass}:class
EOF;

        if (!empty($options)) {
            $content .= <<<EOF
, [
    $options
    ]
EOF         ;
        }

        $content .= <<<EOF
)
EOF;

        return $content;
    }

    /**
     * @return string
     */
    protected function renderTypeString(): string
    {
        switch ($this->formProperty->getFormat()) {
            case 'email':
                $this->useSections[] = 'Symfony\Component\Form\Extension\Core\Type\EmailType';
                $typeClass = 'EmailType';
                break;
            default:
                $this->useSections[] = 'Symfony\Component\Form\Extension\Core\Type\TextType';
                $typeClass = 'TextType';
        }

        return $this->getPropertyWrapper(
            $this->formProperty->getNameCamelCase(),
            $typeClass
        );
    }

    /**
     * @return string
     */
    protected function renderTypeInteger(): string
    {
        $this->useSections[] = 'Symfony\Component\Form\Extension\Core\Type\NumberType';

        return $this->getPropertyWrapper(
            $this->formProperty->getNameCamelCase(),
            'NumberType'
        );
    }

    /**
     * @return string
     */
    protected function renderTypeEntity(): string
    {
        $entityName = $this->formProperty
            ->getReferencedObject()
            ->getName()
        ;

        $this->useSections[] = 'Symfony\Component\Form\Extension\Core\Type\EntityType';
        $this->useSections[] = sprintf('%s\Entity\%s', $this->bundleName, $entityName);

        return $this->getPropertyWrapper(
            $this->formProperty->getNameCamelCase(),
            'EntityType',
            <<<EOF
    'class' => {$entityName}:class,
EOF
        );
    }

    /**
     * @return string
     */
    protected function renderTypeArray(): string
    {
        // todo renderTypeArray
        return '';
    }

    /**
     * @return string
     */
    protected function renderTypeForm(): string
    {
        // todo renderTypeForm
        return '';
    }
}
