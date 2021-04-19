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
 * Single route interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface RouteInterface
{
	/**
	 * Get the logger.
	 *
	 * @return 	LoggerInterface
	 */
	public function getLogger() : LoggerInterface;

    /**
     * See if the route matches.
     * 
     * @param   string  $raw        Pattern to match.
     * @return  bool                True if it matches, else false.  
     * @throws  InvalidArgumentException         
     */
    public function match(string $raw) : bool;

    /**
     * Return the pattern.
     * 
     * @return  string      Pattern.
     */
    public function getPattern(): string;

    /**
     * Return the target.
     * 
     * @return  string      Target.
     */
    public function getTarget(): string;

    /**
     * Is this a special pattern?
     * 
     * @return  bool
     */
    public function isSpecial(): bool;

    /**
     * Get the namespaced class.
     * 
     * @return  string      Full class name.
     */
    public function getNamespacedClass(): string;

    /**
     * Set namespaced class.
     * 
     * @param   string    $rawRoute     Raw route.
     * @param   string    $prefix       Class prefix.
     * @return  void.
     */
    public function setNamespacedClass(string $prefix = null);

    /**
     * Get the parameters.
     * 
     * @return  array 
     */
    public function getParameters(): array;

    /**
     * Do we have parameters?
     * 
     * @return  bool
     */
    public function hasParameters(): bool;
}
