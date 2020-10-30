<?php


namespace ZMiddleware\Pipeline;


class Pipeline
{
    /**
     * callback method
     * @var string
     */
    protected $method = 'handle';

    /**
     * The object being passed throw the pipeline
     * @var mixed
     */
    protected $passable;

    /**
     * The array of class pipes
     * @var array
     */
    protected $pipes = [];

    public function send($passable)
    {
        $this->passable = $passable;
        return $this;
    }

    public function through($pipes)
    {
        $this->pipes = $pipes;
        return $this;
    }

    public function then(\Closure $destination)
    {
        $pipeline = array_reduce(array_reverse($this->pipes), $this->getSlice(), $destination);
        return $pipeline($this->passable);
    }

    protected function getSlice()
    {
        return function($stack, $pipe){
            return function ($passable) use ($stack, $pipe) {
                $parameters = [$passable, $stack];
                return $pipe->{$this->method}(...$parameters);
            };
        };
    }
}