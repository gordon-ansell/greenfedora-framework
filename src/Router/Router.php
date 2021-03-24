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

use GreenFedora\Router\RouterInterface;
use GreenFedora\Router\Route;
use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerInterface;

/**
 * Router.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Router implements RouterInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

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
     * @param   ArrInterface     $routeSpec      Route specifications.
     * @return  void
     */
    public function __construct(ArrInterface $routeSpec, ContainerInterface $container)
    {
        $this->container = $container;
        $this->routeSpec = $routeSpec;
        $this->loadRoutes($this->routeSpec->routes);
    }

	/**
	 * Get the logger.
	 *
	 * @return 	LoggerInterface
	 */
	public function getLogger() : LoggerInterface
    {
		return $this->getInstance('logger');
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
        $this->trace4(sprintf('Loaded %c routes.', sizeof($this->routes)));
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
