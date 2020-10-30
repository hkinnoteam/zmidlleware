# How to use Zmiddleware  

## Install  
> composer require "hkinnoteam/zmidlleware"  

## Implementation
* create a class which implement **ZMiddleware\Contract\MiddlewareInterface**
```php
use ZMiddleware\Contract\MiddlewareInterface;
class AssignResultMiddleware implements MiddlewareInterface
{

    public function handle($pass, \Closure $next)
    {
        // before request
        $validate = true;
        if ($validate){
            return 'not allow';
        }
        
        // request method
        $number = $next($pass);
        
        //after request
        $number->total_qty = 100;
        return $number;
    }
}
```
## Add annotation into your api method
```php
use AssignResultMiddleware;
use ZMiddleware\Annotations\Middleware;
class Foo
{

    /**
     * single middleware
     * 
     * @Middleware(AssignResultMiddleware::class)
     * @return object
     */
    public static function Foo(): object
    {
        $foo = new \stdClass();
        return $foo;
    }
}
```
> you can also assign multiple middlewares  
```php
use AssignBooMiddleware;
use AssignFooMiddleware;
use ZMiddleware\Annotations\Middlewares;
use ZMiddleware\Annotations\Middleware;
class Foo
{

    /**
     * multiple middlewares
     *
     * @Middlewares({
     *     @Middleware(AssignBooMiddleware::class)
     *     @Middleware(AssignFooMiddleware::class)
     * })
     * @return object
     */
    public static function Foo(): object
    {
        $foo = new \stdClass();
        return $foo;
    }
}
```