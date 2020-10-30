<?php


namespace ZMiddleware\Annotations;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\FileCacheReader;

abstract class AbstractAnnotation
{
    public static function collectMethod(string $className, string $method):array
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
            $reflectionMethod = $reflectionClass->getMethod($method);
            $reader = new FileCacheReader(new AnnotationReader(), '../storage/annotation',true);
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

    public static function getClasses($middlewares): array
    {
        $middlewaresResult = [];
        foreach ($middlewares as $middleware){
            $middlewaresResult[] = self::getClass($middleware);
        }
        return $middlewaresResult;
    }

    public static function getClass($middleware): string
    {
        return $middleware->middleware;
    }
}