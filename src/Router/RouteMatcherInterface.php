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

use GreenFedora\Logger\LoggerInterface;

/**
 * Route matcher interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface RouteMatcherInterface
{
    /**
     * See if the route matches.
     * 
     * @param   string       $pattern    Patterm to match against.
     * @param   string|null  $raw        Pattern to match.
     * @return  bool                     True if it matches, else false.  
     * @throws  InvalidArgumentException         
     */
    public function match(string $pattern, ?string $raw = null) : bool;
}
