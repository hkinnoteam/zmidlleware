<?php


namespace ZMiddleware\Contract;


interface MiddlewareInterface
{
    public function handle($pass, \Closure $next);
}