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

/**
 * Abstract route matcher.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractRouteMatcher implements RouteMatcherInterface
{
    /**
     * Parameters.
     * @var array
     */
    protected $parameters = [];

    /**
     * See if the route matches.
     * 
     * @param   string       $pattern    Patterm to match against.
     * @param   string|null  $raw        Pattern to match.
     * @return  bool                     True if it matches, else false.  
     * @throws  InvalidArgumentException         
     */
    abstract public function match(string $pattern, ?string $raw = null) : bool;

    /**
     * See if we have parameters.
     * 
     * @return  bool
     */
    public function hasParameters(): bool
    {
        return (count($this->parameters) > 0);
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
}

