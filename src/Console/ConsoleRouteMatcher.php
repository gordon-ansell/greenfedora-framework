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

use GreenFedora\Router\AbstractRouteMatcher;

use GreenFedora\Console\Exception\InvalidArgumentException;
use GreenFedora\Router\RouteMatcherInterface;

/**
 * Console route matcher.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleRouteMatcher extends AbstractRouteMatcher implements RouteMatcherInterface
{
    /**
     * See if the route matches.
     * 
     * @param   string       $pattern    Patterm to match against.
     * @param   string|null  $raw        Pattern to match.
     * @return  bool                     True if it matches, else false.  
     * @throws  InvalidArgumentException         
     */
    public function match(string $pattern, ?string $raw = null) : bool
    {
        $this->parameters = [];
		$sp = explode('|', $pattern);
		$go = getopt($sp[0], explode(',', $sp[1]));
        if (array_key_exists($sp[2], $go)) {
            $this->parameters = $go;
            return true;
        }
        return false;
    }
}

