<?php

namespace Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType;

use Requestum\ApiGeneratorBundle\Model\FormProperty;

/**
 * Interface FormPropertyTypeAbstract
 *
 * @package Requestum\ApiGeneratorBundle\Service\Render\Form\FormPropertyType
 */
abstract class FormPropertyTypeAbstract implements FormPropertyTypeInterface
{
    /** @var string */
    protected string $bundleName;

    /** @var array */
    protected array $supportedConstraints;

    /**
     * FormPropertyTypeAbstract constructor.
     *
     * @param string $bundleName
     * @param array $supportedConstraints
     */
    public function __construct(string $bundleName, array $supportedConstraints = [])
    {
        $this->bundleName = $bundleName;
        $this->supportedConstraints = $supportedConstraints;
    }

    /**
     * @param FormProperty $formProperty
     *
     * @return array
     */
    protected function getNeededConstraints(FormProperty $formProperty): array
    {
        $constraints = [];

        foreach ($this->supportedConstraints as $constraint) {
            if (!$constraint::isNeeded($formProperty)) {
                continue;
            }

            $constraints[] = $constraint::getConstraintContent($formProperty);
        }

        return $constraints;
    }

    /**
     * @param string $name
     * @param string $typeClass
     * @param array $constraints
     * @param string|null $optionsContent
     *
     * @return string
     */
    protected static function wrapProperty(
        string $name,
        string $typeClass,
        array $constraints,
        string $optionsContent = ''
    ): string {
        if (!empty($constraints)) {
            $optionsContent .= self::wrapPropertyConstraints($constraints);
        }

        $content = <<<EOF
\n    ->add('{$name}', {$typeClass}::class
EOF;

        if (!empty($optionsContent)) {
            $content .= <<<EOF
, [
    $optionsContent
    ]
EOF         ;
        }

        $content .= <<<EOF
)
EOF;

        return $content;
    }

    /**
     * @param array $constraints
     *
     * @return string
     */
    protected static function wrapPropertyConstraints(array $constraints)
    {
        $constraintsContent = implode(",\n", $constraints);

        return <<<EOF
    'constraints' => [
            {$constraintsContent},
        ]
EOF;
    }
}
