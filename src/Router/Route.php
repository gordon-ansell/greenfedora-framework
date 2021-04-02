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
use GreenFedora\DependencyInjection\ContainerAwareTrait;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerInterface;

use GreenFedora\Router\Exception\InvalidArgumentException;

/**
 * Single route.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Route implements RouteInterface
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

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
    public function __construct(string $pattern, string $target, ContainerInterface $container)
    {
        $this->pattern = $pattern;
        $this->target = $target;
        $this->container = $container;
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
     * See if the route matches.
     * 
     * @param   string  $pattern    Pattern to match.
     * @return  bool                True if it matches, else false.  
     * @throws  InvalidArgumentException         
     */
    public function match(string $pattern) : bool
    {
        $pattern = '/' . trim($pattern, '/') . '/';
        if ('//' == $pattern) $pattern = '/';


        $pat = '/' . trim($this->pattern, "/");
        $frigged = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)?(?:(\/))', preg_quote($pat)) . "$@D";

        //$pattern = '/' . trim($pattern, '/') . '/';

        //$tail = "\/.*$|$";
        //$quoted = preg_quote('/' . trim($this->pattern, '/'), '@') . $tail;
        //$quoted = '/' . trim($this->pattern, '/') . '/';
        //$quoted = preg_quote($quoted, '@');
        $matches = [];

        $this->trace4(sprintf("Trying to match '%s' against '%s'.", $pattern, $frigged));

        $result = preg_match($frigged, $pattern, $matches);

        if (false === $result) {
            throw new InvalidArgumentException(sprintf("Invalid regex in router: %s",  
                array_flip(get_defined_constants(true)['pcre'])[preg_last_error()]));
        } else if (1 == $result) {
            $this->trace4(sprintf("MATCHED '%s' against '%s'.", $pattern, $frigged));
            if (count($matches) > 1) {
                array_shift($matches);
                for ($i = 0; $i < count($matches); $i++) {
                    $this->parameters[] = trim($matches[$i], "/");
                }
            }
            print_r($matches);
            print_r($this->parameters);
            return true;
        }
        
        /*
        if ($pattern == $this->pattern) {
            $this->trace4(sprintf("MATCHED '%s' against '%s'.", $pattern, $this->pattern));
            return true;
        }
        */
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
}
