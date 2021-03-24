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
namespace GreenFedora\Router;

use GreenFedora\Router\RouteInterface;

/**
 * Single route.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Route implements RouteInterface
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
     * @param   string  $pattern    Pattern to match.
     * @return  bool                True if it matches, else false.           
     */
    public function match(string $pattern) : bool
    {
        if ($pattern == $this->pattern) {
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
     * Created namespaced class.
     * 
     * @param   string    $rawRoute     Raw route.
     * @param   string    $prefix       Class prefix.
     * @return  array                   Namespaced class and any parameters.
     */
    public function getNamespacedClass(string $prefix = null)
    {
        $class = '';

        if ('\\' != $this->target[1]) {
			if ($prefix) {
				$class = $prefix;
			}
		}

		$class = '\\' . trim($class, '\\') . '\\' . trim($this->target, '\\');

        return $class;
    }
}
