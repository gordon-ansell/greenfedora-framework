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
namespace GreenFedora\Console;

use GreenFedora\Console\ConsoleRouteInterface;

use GreenFedora\Router\Exception\InvalidArgumentException;

/**
 * Single console route.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleRoute implements ConsoleRouteInterface
{
    /**
     * Route pattern.
     * @var string|null
     */
    protected $pattern = null;

    /**
     * Route target.
     * @var string|null
     */
    protected $target = null;

    /**
     * Namespaced class.
     * @var string|null
     */
    protected $namespacedClass = null;

    /**
     * Parameters.
     * @var array
     */
    protected $parameters = [];

    /**
     * Constructor.
     * 
     * @param   string   $pattern      Route pattern.
     * @param   string   $target       Route target.
     * @return  void
     */
    public function __construct(string $pattern, string $target)
    {
        $this->pattern = $pattern;
        $this->target = $target;
    }

    /**
     * See if the route matches.
     * 
     * @param   array   $args       Arguments with pattern to match.
     * @return  bool                True if it matches, else false.  
     * @throws  InvalidArgumentException         
     */
    public function match($raw) : bool
    {
        if (preg_match('#' . $this->pattern . '#', $raw, $matches)) {
            if (array_key_exists('params', $matches)) {
                $this->parameters = explode('/', trim($matches['params'], '/'));
            }
            return true;
        }        
        return false;
    }

    /**
     * Return the pattern.
     * 
     * @return  string      Pattern.
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Return the target.
     * 
     * @return  string      Target.
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * Is this a special pattern?
     * 
     * @return  bool
     */
    public function isSpecial(): bool
    {
        return '_' == $this->pattern[0] and '_' == $this->pattern[-1];
    }

    /**
     * Get the namespaced class.
     * 
     * @return  string      Full class name.
     */
    public function getNamespacedClass(): string
    {
        return $this->namespacedClass;
    }

    /**
     * Set namespaced class.
     * 
     * @param   string    $rawRoute     Raw route.
     * @param   string    $prefix       Class prefix.
     * @return  void.
     */
    public function setNamespacedClass(string $prefix = null)
    {
        $class = '';

        if ('\\' != $this->target[1]) {
			if ($prefix) {
				$class = $prefix;
			}
		}

		$class = '\\' . trim($class, '\\') . '\\' . trim($this->target, '\\');

        $this->namespacedClass = $class;
    }

    /**
     * Get the parameters.
     * 
     * @return  array 
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Do we have parameters?
     * 
     * @return  bool
     */
    public function hasParameters(): bool
    {
        return (count($this->parameters) > 0) ? true : false;
    }
}
