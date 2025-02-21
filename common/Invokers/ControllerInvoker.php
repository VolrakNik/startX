<?php

namespace Common\Invokers;

use Common\Requests\Request;
use Common\Requests\RequestValidator;
use ReflectionException;
use ReflectionMethod;

class ControllerInvoker
{
    /**
     * @throws ReflectionException
     */
    public function call(object $controller, string $method): mixed
    {
        $reflection = new ReflectionMethod($controller, $method);
        $parameters = $reflection->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $parameterType = $parameter->getType();
            if (
                $parameterType &&
                ($parameterTypeName = $parameterType->getName()) &&
                is_a($parameterTypeName, Request::class, true)
            ) {
                $request = new $parameterTypeName();
                $requestValidator = new RequestValidator();
                $requestValidator->validate($request->rules(), $request->all());
                $request->setValidated($requestValidator->getValidatedFields());
                $args[] = $request;
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[] = $parameter->getDefaultValue();
            }
        }

        return $reflection->invokeArgs($controller, $args);
    }
}