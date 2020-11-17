<?php


namespace ZMiddleware\Annotations;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ApcuCache;

abstract class AbstractAnnotation
{
    /**
     * collect middleware
     * @param string $className
     * @param string $method
     * @return array|string[]
     */
    public static function collectMethod(string $className, string $method):array
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
            $reflectionMethod = $reflectionClass->getMethod($method);
            $reader = new CachedReader(new AnnotationReader(), new ApcuCache());
            $annotation = $reader->getMethodAnnotations($reflectionMethod);
            if (empty($annotation)){
                throw new \RuntimeException('not need handle');
            }
            $annotation = $annotation[0];
            if ($annotation instanceof Middlewares){
                return self::getClasses($annotation->middlewares);
            }
            if ($annotation instanceof Middleware){
                return [self::getClass($annotation)];
            }
        } catch (\ReflectionException $e) {
            throw new \RuntimeException('failed to get annotation classes');
        }
    }

    /**
     * get batch middleware
     * @param $middlewares
     * @return array
     */
    public static function getClasses($middlewares): array
    {
        $middlewaresResult = [];
        foreach ($middlewares as $middleware){
            $middlewaresResult[] = self::getClass($middleware);
        }
        return $middlewaresResult;
    }

    /**
     * get single middleware
     * @param $middleware
     * @return string
     */
    public static function getClass($middleware): string
    {
        return $middleware->middleware;
    }
}