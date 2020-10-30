<?php


namespace ZMiddleware;


use ZMiddleware\Annotations\AbstractAnnotation;
use ZMiddleware\Pipeline\Pipeline;

class ZRoute
{
    public static function dispatch($reflection, $pass, array $class)
    {
        try {
            [$class, $method] = $class;
            $route     = new self();
            $pipelines = new Pipeline();
            $pipes     = $route->prepareHandlers(AbstractAnnotation::collectMethod($class, $method));

            return $pipelines->send($pass)->through($pipes)->then(function ($pass) use ($reflection){
                return $reflection->invokeArgs(null, $pass);
            });
        }catch(\Exception $e){
            var_dump($e->getMessage());
            return $reflection->invokeArgs(null, $pass);
        }

    }

    private function prepareHandlers(array $handlers): array
    {
        $classes = [];
        foreach ($handlers as $handler){
            $classes [] = new $handler();
        }
        return $classes;
    }
}