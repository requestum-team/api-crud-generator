<?php

namespace Requestum\ApiGeneratorBundle\Model\Generator;

/**
 * Class GeneratorMethodModel
 *
 * @package Requestum\ApiGeneratorBundle\Model\Generator
 */
class GeneratorMethodModel
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     *
     * @example public, protected, private
     */
    protected string $accessLevel;

    /**
     * @var string
     */
    protected string $body;

    /**
     * @var GeneratorParameterModel[]
     */
    protected array $parameters = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return GeneratorMethodModel
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccessLevel(): string
    {
        return $this->accessLevel;
    }

    /**
     * @param string $accessLevel
     *
     * @return GeneratorMethodModel
     */
    public function setAccessLevel(string $accessLevel)
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return GeneratorMethodModel
     */
    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return GeneratorParameterModel[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param GeneratorParameterModel[] $parameters
     *
     * @return GeneratorMethodModel
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param GeneratorParameterModel $parameter
     *
     * @return GeneratorMethodModel
     */
    public function addParameters(GeneratorParameterModel $parameter)
    {
        $parameter->setPosition(count($this->parameters));

        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @return GeneratorParameterModel[]
     */
    public function getInputParameters(): array
    {
        return array_filter($this->getParameters(), function (GeneratorParameterModel $el) {
            return !$el->isReturn();
        });
    }
}
