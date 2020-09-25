<?php

namespace Requestum\ApiGeneratorBundle\Model;

use Requestum\ApiGeneratorBundle\Helper\ActionHelper;

/**
 * Class Action
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class Action extends BaseModel implements ModelInterface
{
    const DEFAULT_ACTION_LIST   = 'Requestum\\ApiBundle\\Action\\ListAction';
    const DEFAULT_ACTION_CREATE = 'Requestum\\ApiBundle\\Action\\CreateAction';
    const DEFAULT_ACTION_UPDATE = 'Requestum\\ApiBundle\\Action\\UpdateAction';
    const DEFAULT_ACTION_DELETE = 'Requestum\\ApiBundle\\Action\\DeleteAction';
    const DEFAULT_ACTION_FETCH  = 'Requestum\\ApiBundle\\Action\\FetchAction';

    const DEFAULT_PARENT_NAME = 'core.action.abstract';

    /**
     * @var string
     */
    private string $prefix;

    /**
     * @var string|null
     */
    private ?string $className = null;

    /** @var Entity|null */
    private ?Entity $entity = null;

    /** @var Form|null */
    private ?Form $form = null;

    /**
     * @var string[]
     */
    private array $arguments = [];

    /**
     * @var string[]
     */
    private array $calls = [];


    public function __construct(string $prefix = 'action')
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getServiceName(): string
    {
        $suffix = $this->getSuffix();

        return implode('.', [$this->prefix, ...$this->getServicePath(), $suffix]);
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        if (!is_null(parent::getParent())) {
            return parent::getParent();
        }

        return self::DEFAULT_PARENT_NAME;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * @param string $className
     *
     * @return Action
     */
    public function setClassName(string $className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return Entity|null
     */
    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    public function setEntity(Entity $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return Form|null
     */
    public function getForm(): ?Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param string[] $arguments
     *
     * @return Action
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param string $argument
     *
     * @return Action
     */
    public function addArguments(string $argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getCalls(): array
    {
        return $this->calls;
    }

    /**
     * @param string[] $calls
     *
     * @return Action
     */
    public function setCalls(array $calls)
    {
        $this->calls = $calls;

        return $this;
    }

    /**
     * @param string $call
     *
     * @return Action
     */
    public function addCall(string $call)
    {
        $this->calls[] = $call;

        return $this;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getSuffix(): string
    {
        switch ($this->getClassName()) {
            case Action::DEFAULT_ACTION_FETCH:
                return ActionHelper::ACTION_FETCH;
            case Action::DEFAULT_ACTION_LIST:
                return ActionHelper::ACTION_LIST;
            case Action::DEFAULT_ACTION_CREATE:
                return ActionHelper::ACTION_CREATE;
            case Action::DEFAULT_ACTION_UPDATE:
                return ActionHelper::ACTION_UPDATE;
            case Action::DEFAULT_ACTION_DELETE:
                return ActionHelper::ACTION_DELETE;
        }

        throw new \Exception(sprintf(
            'Method "%s" not allowed. Allowed methods %s', $this->getMethod(), self::getAllowedMethodsString()
        ));
    }
}
