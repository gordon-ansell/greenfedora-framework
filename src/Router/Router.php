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

use GreenFedora\Router\Route;
use GreenFedora\Arr\Arr;

/**
 * Router.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Router
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
     * @var Arr|null
     */
    protected $routeSpec = null;

    /**
     * Routes.
     * @var Route[]
     */
    protected $routes = [];

    /**
     * Constructor.
     * 
     * @param   Arr     $routeSpec      Route specifications.
     * @return  void
     */
    public function __construct($routeSpec)
    {
        $this->routeSpec = $routeSpec;
        $this->loadRoutes($this->routeSpec->routes);
    }

    /**
     * Load the routes.
     * 
     * @param   Arr     $routes         Routes to load.
     * @return  void
     */
    protected function loadRoutes($routes)
    {
        foreach($routes as $pattern => $target) {
            $this->routes[] = new Route($pattern, $target);
        }
    }

    /**
     * Match a route.
     * 
     * @param   string  $pattern    Route pattern to match.
     * @return 
     */
    public function match(string $pattern)
    {
        // Try to match real routes.
        $matched = null;
        foreach ($this->routes as $key => $route) {
            if ($route->match($pattern)) {
                $matched = $route;
                break;
            }
        }
    }
}
