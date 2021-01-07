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
    public static function dispatch($pass, array $class)
    {
        [$class, $method] = $class;
        $controller = di($class);
        try {
            $route     = new self();
            $pipelines = new Pipeline();
            $pipes     = $route->prepareHandlers(AbstractAnnotation::collectMethod($class, $method));

            return $pipelines->send($pass)->through($pipes)->then(function ($pass) use ($controller, $method){
                return $controller->{$method}(...$pass);
            });
        }catch(\Exception $e){
            throw $e;
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
            $classes [] = di($handler);
        }
        return $classes;
    }
}