<?php

namespace Requestum\ApiGeneratorBundle\Model;

class Action extends BaseModel
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
     * @var string
     */
    private ?string $className = null;

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
     * @return string
     */
    public function getClassName(): string
    {
        if (!is_null($this->className)) {
            return $this->className;
        }

        return $this->getDefaultClassName();
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

    private function getDefaultClassName(): string {
        switch ($this->getMethod()) {
            case self::ALLOWED_METHOD_GET:
                return $this->hasAttributs() ? self::DEFAULT_ACTION_FETCH: self::DEFAULT_ACTION_LIST;
                break;

            case self::ALLOWED_METHOD_POST:
                return self::DEFAULT_ACTION_CREATE;
                break;

            case self::ALLOWED_METHOD_PATCH:
            case self::ALLOWED_METHOD_PUT:
                return self::DEFAULT_ACTION_UPDATE;
                break;

            case self::ALLOWED_METHOD_DELETE:
                return self::DEFAULT_ACTION_DELETE;
                break;

            default:
                throw new \Exception(sprintf('Allowed methods %s', self::getAllowedMethodsString()));
        }

    }
}
