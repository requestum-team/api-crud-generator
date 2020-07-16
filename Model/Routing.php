<?php

namespace Requestum\ApiGeneratorBundle\Model;

class Routing extends BaseModel
{

    /**
     * @var string
     */
    private string $url;

    public function __construct(?Action $action = null)
    {
        if (!is_null($action)) {
            $this->setName($action->getName());
            $this->setMethod($action->getMethod());
            $this->setParent($action->getServiceName());
            $this->setServicePath($action->getServicePath());
        }
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Routing
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return \StdClass
     */
    public function getServiceName(): string
    {
        $suffix = $this->getSuffix();

        return implode('.', [...$this->getServicePath(), $suffix]);
    }

    public function getControllerName(): string
    {
        return implode('::', [$this->getParent(), 'executeAction']);
    }
}
