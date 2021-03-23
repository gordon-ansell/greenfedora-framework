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
 * HTTP router.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class HttpRouter
{
    /**
     * Match constants.
     */
	const MATCH_ANY = '.+';
	const MATCH_NUM = '[[:digit:]]+';
	const MATCH_ALNUM = '[[:alnum:]]+';
	const MATCH_ALPHA = '[[:alpha:]]+';
	const MATCH_SEGMENT = '[^/]*';

    /**
     * Route specifications.
     * @var array
     */
    protected $routeSpec = [];

    /**
     * Constructor.
     * 
     * @param   array   $routeSpec      Route specifications.
     * @return  void
     */
    public function __construct(array $routeSpec = [])
    {
        $this->routeSpec = $routeSpec;
    }
}
