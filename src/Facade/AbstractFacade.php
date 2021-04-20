<?php

/**
 * This file is part of the GordyAnsell GreenFedora PHP framework.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);
namespace GreenFedora\Facade;

use GreenFedora\DI\ContainerInterface;
use GreenFedora\DI\Container;
use GreenFedora\Facade\Exception\RuntimeException;

/**
 * Abstract facade.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractFacade
{
    /**
     * The container.
     * @var ContainerInterface
     */
    protected static $container = null;

    /**
     * Resolved instances.
     * @var array
     */
    protected static $resolved = [];

    /**
     * Set the container.
     * 
     * @param   ContainerInterface  $container  Container.
     * @return  void
     */
    protected static function setContainer(ContainerInterface $container)
    {
        static::$container = $container;
    }

    /**
     * Get the container.
     * 
     * @return  ContainerInterface
     */
    protected static function getContainer(): ContainerInterface
    {
        if (is_null(static::$container)) {
            static::$container = Container::getInstance();
        }
        return static::$container;
    }

    /**
     * Get the container.
     */

    /**
     * Get the facade key.
     * 
     * @return  string
     */
    abstract protected static function facadeKey(): string;

    /**
     * Resolve an instance for the facade.
     * 
     * @param   string  $name   Name to resolve.
     * @return  mixed
     */
    protected static function resolveInstance(string $name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolved[$name])) {
            return static::$resolved[$name];
        }

        return static::$resolved[$name] = static::getContainer()->get($name);
    }

    /**
     * Get the required entry from the container.
     * 
     * @return  mixed
     */
    protected static function facadeRoot()
    {
        if (!static::getContainer()->has(static::facadeKey())) {
            throw new RuntimeException(sprintf("Facade class '%s' cannot find container key '%s'", 
                __CLASS__, static::facadeKey()));
        }
        return static::resolveInstance(static::facadeKey());
    }

    /**
     * Static call into class we're facading.
     * 
     * @param   string      $method     Method to call.
     * @param   iterable    $args       Arguments for method.
     * @return  mixed
     */
    public static function __callStatic(string $method, iterable $args)
    {
        $instance = static::facadeRoot();
        return $instance->$method(...$args);
    }

}