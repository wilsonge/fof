<?php

class JToolbarHelper
{
    protected static $methodStack = array();

    public static function __callStatic($name, $arguments)
    {
        // I have to save every call, because some methods (ie divider) would be called more than once
        self::$methodStack[$name][] = $arguments;
    }

    public static function getStack()
    {
        return self::$methodStack;
    }

    public static function resetStack()
    {
        self::$methodStack = array();
    }
}