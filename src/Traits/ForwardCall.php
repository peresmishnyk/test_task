<?php

namespace Peresmishnyk\Task\Traits;

trait ForwardCall
{
    public static function __callStatic($name, $args)
    {
        return forward_static_call_array([static::class, $name], $args);
    }

    public function __call($name, $args)
    {
        return forward_static_call_array([$this, $name], $args);
    }
}