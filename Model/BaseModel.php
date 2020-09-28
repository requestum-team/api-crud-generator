<?php

namespace Requestum\ApiGeneratorBundle\Model;

/**
 * Class BaseModel
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
class BaseModel
{
    const ALLOWED_METHOD_GET    = 'get';
    const ALLOWED_METHOD_POST   = 'post';
    const ALLOWED_METHOD_PATCH  = 'patch';
    const ALLOWED_METHOD_PUT    = 'put';
    const ALLOWED_METHOD_DELETE = 'delete';

    /**
     * @return string[]
     */
    public static function getAllowedMethods()
    {
        return [
            self::ALLOWED_METHOD_GET,
            self::ALLOWED_METHOD_POST,
            self::ALLOWED_METHOD_PATCH,
            self::ALLOWED_METHOD_PUT,
            self::ALLOWED_METHOD_DELETE,
        ];
    }

    /**
     * @param $method
     *
     * @return bool
     */
    public static function isAllowedMethods($method): bool
    {
        return in_array($method, self::getAllowedMethods());
    }

    /**
     * @return string
     */
    public static function getAllowedMethodsString()
    {
        return strtoupper(implode(', ', [
            self::ALLOWED_METHOD_GET,
            self::ALLOWED_METHOD_POST,
            self::ALLOWED_METHOD_PATCH,
            self::ALLOWED_METHOD_PUT,
            self::ALLOWED_METHOD_DELETE,
        ]));
    }

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string[]
     */
    private array $servicePath = [];

    /**
     * @var string
     */
    private ?string $parent = null;

    /**
     * @var string
     */
    private string $method;

    /**
     * @var boolean
     */
    private $hasAttributs = false;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getServicePath(): array
    {
        return $this->servicePath;
    }

    /**
     * @param array $servicePath
     *
     * @return $this
     */
    public function setServicePath(array $servicePath)
    {
        $this->servicePath = $servicePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     *
     * @return $this
     */
    public function setParent(string $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = strtolower($method);

        return $this;
    }

    /**
     * @param bool $hasAttributs
     *
     * @return $this
     */
    public function setHasAttributs(bool $hasAttributs)
    {
        $this->hasAttributs = $hasAttributs;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAttributs(): bool
    {
        return $this->hasAttributs;
    }
}
