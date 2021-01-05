<?php

namespace App\Tests\Unit\Utils;

use ReflectionClass;
use ReflectionException;

trait PrivateAccessor
{
    public static function setValue($object, string $property, $value): void
    {
        try {
            $reflection = new ReflectionClass($object);
            $property = $reflection->getProperty($property);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        } catch (ReflectionException $e) {
            echo PHP_EOL . $e->getMessage();
        }
    }

    public static function callMethod($obj, $name, array $args)
    {
        try {
            $class = new ReflectionClass($obj);
            $method = $class->getMethod($name);
            $method->setAccessible(true);
            return $method->invokeArgs($obj, $args);
        } catch (ReflectionException $e) {
            echo PHP_EOL . $e->getMessage();
            return false;
        }
    }
}
