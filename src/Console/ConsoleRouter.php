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

use GreenFedora\Console\ConsoleRouterInterface;
use GreenFedora\Router\Route;
use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerInterface;

/**
 * Console router.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleRouter implements ConsoleRouterInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

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
     * Command line args.
     * @var array
     */
    protected $args = [];

    /**
     * Constructor.
     * 
     * @param   ArrInterface            $routeSpec      Route specifications.
     * @param   array                   $args           Command line args.
     * @param   LoggerInterface|null    $logger         Logger.
     * @return  void
     */
    public function __construct(ArrInterface $routeSpec, array $args, ?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->routeSpec = $routeSpec;
        $this->args = $args;
        $this->loadRoutes($this->routeSpec->routes);
    }

	/**
	 * Get the logger.
	 *
	 * @return 	LoggerInterface
	 */
	public function getLogger() : LoggerInterface
    {
		return $this->logger;
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
            $this->routes[$pattern] = new ConsoleRoute($pattern, $target, $this->args);
        }
        $this->trace4(sprintf('Loaded %s routes.', sizeof($this->routes)));
    }

    /**
     * Match a route.
     * 
     * @param   string  $pattern    Route pattern to match.
     * @return  array               Matched route object and parameters.
     */
    public function match(?string $pattern = null): ?array
    {
        $matched = null;

        // Try to match real routes.
        foreach ($this->routes as $key => $route) {

            if ($route->isSpecial()) {
                continue;
            }

            if ($route->match($pattern)) {
                $this->trace4(sprintf("Matched pattern '%s' against route pattern '%s', target is '%s'.", 
                    $pattern, $route->getPattern(), $route->getTarget()));
                $matched = $route;
                break;
            } else {
                $this->trace4(sprintf("No match for pattern '%s' against route pattern '%s'.", 
                    $pattern, $route->getPattern()));
            }
        }

        if (null === $matched) {
            if (array_key_exists('_default_', $this->routes) and ('/' == $pattern or '' == $pattern)) {
                $matched = $this->routes['_default_'];
                $this->trace4("Matched default route.");
            }
        }

        if (null === $matched) {
            if (array_key_exists('_404_', $this->routes)) {
                $matched = $this->routes['_404_'];
                $this->trace4("Matched 404 route.");
            }
        }

        $prefix = $this->routeSpec->has('prefixNamespace') ? $this->routeSpec->prefixNamespace : null;

        $matched->setNamespacedClass($prefix);

        $params = ($matched->hasParameters()) ? $matched->getParameters() : [];

        return [$matched, $params];
    }
}
