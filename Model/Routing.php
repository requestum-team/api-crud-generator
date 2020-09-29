<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class Routing
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class Routing extends BaseModel implements ModelInterface
{
    /** @var Action */
    private Action $action;

    /**
     * @param Action $action
     *
     * @throws \Exception
     */
    public function __construct(Action $action)
    {
        $this->action = $action;

        $this
            ->setName($this->action->getName())
            ->setMethod($this->action->getMethod())
            ->setParent($this->action->getServiceName())
            ->setServicePath($this->action->getServicePath())
        ;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->action->getPath();
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getServiceName(): string
    {
        $suffix = $this->action->getSuffix();

        return implode('.', [...$this->getServicePath(), $suffix]);
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return implode('::', [$this->getParent(), 'executeAction']);
    }
}
