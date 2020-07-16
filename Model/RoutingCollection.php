<?php

namespace Requestum\ApiGeneratorBundle\Model;

class RoutingCollection extends BaseAbstractCollection
{
    public function dump(): array
    {
        $result = [];
        foreach ($this->getElements() as $key => $routes) {
            /** @var Routing $route */
            foreach ($routes as $route) {

                $result[$key][$route->getServiceName()] = [
                    'path' => $route->getUrl(),
                    'methods' => strtoupper($route->getMethod()),
                    'controller' => $route->getControllerName(),
                ];
            }
        }

        return $result;
    }
}
