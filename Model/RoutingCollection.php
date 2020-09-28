<?php

namespace Requestum\ApiGeneratorBundle\Model;

class RoutingCollection extends BaseAbstractCollection
{
    /**
     * @return array
     *
     * @throws \Exception
     */
    public function dump(): array
    {
        $result = [];
        foreach ($this->getElements() as $key => $routes) {
            /** @var Routing $route */
            foreach ($routes as $route) {

                $result[$key][$route->getServiceName()] = [
                    'path'       => $route->getPath(),
                    'methods'    => strtoupper($route->getMethod()),
                    'controller' => $route->getControllerName(),
                ];
            }
        }

        return $result;
    }
}
