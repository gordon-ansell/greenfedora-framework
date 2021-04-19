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
 * Router interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface RouterInterface
{
	/**
	 * Get the logger.
	 *
	 * @return 	LoggerInterface
	 */
	public function getLogger() : LoggerInterface;

    /**
     * Match a route.
     * 
     * @param   string  $pattern    Route pattern to match.
     * @return  array               Matched route object and parameters.
     */
    public function match(string $pattern): ?array;
}
