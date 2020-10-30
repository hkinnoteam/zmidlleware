<?php


namespace ZMiddleware;


use ZMiddleware\Annotations\AbstractAnnotation;
use ZMiddleware\Pipeline\Pipeline;

class ZRoute
{
    /**
     * dispatch request
     * @param $reflection
     * @param $pass
     * @param array $class
     * @return mixed
     */
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
            return $reflection->invokeArgs(null, $pass);
        }

    }

    /**
     * create handler instance
     * @param array $handlers
     * @return array
     */
    private function prepareHandlers(array $handlers): array
    {
        $classes = [];
        foreach ($handlers as $handler){
            $classes [] = new $handler();
        }
        return $classes;
    }
}