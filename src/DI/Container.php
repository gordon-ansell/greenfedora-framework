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
namespace GreenFedora\DI;

use GreenFedora\DI\ContainerInterface;
use GreenFedora\DI\Exception\InvalidArgumentException;
use GreenFedora\DI\Exception\OutOfBoundsException;

use GreenFedora\DI\ContainerMapEntryClass;
use GreenFedora\DI\ContainerMapEntrySingleton;
use GreenFedora\DI\ContainerMapEntryValue;
use GreenFedora\DI\ContainerMapEntryInterface;

use \ReflectionClass;

/**
 * Dependency injection container.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Container implements ContainerInterface
{
    /**
     * The map of things.
     * @var ContainerMapEntryInterface[]
     */
    private $map = [];

	/**
	 * Static singleton instance.
	 * @var ContainerInterface
	 */
	private static $instance = null;

	/**
	 * Get the static instance.
	 * 
	 * @return 	ContainerInterface
	 */
	public static function getInstance(): ContainerInterface
	{
		if (null === self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Check class doc comments for injections.
	 * 
	 * @param 	ReflectionClass 	$reflection 	Reflector.
	 * @param 	object 				$obj 			Object in question.
	 * @return	object
	 * @throws 	InvalidArgumentException
	 */
	protected function checkClassDocComments(ReflectionClass $reflection, object $obj): object
	{
		if ($doc = $reflection->getDocComment()) {

			$lines = explode("\n", $doc);

			foreach($lines as $line) {

				if (count($parts = explode("@Inject", $line)) > 1) {

					$parts = explode(" ", $parts[1]);

					if (count($parts) > 1) {

						$key = $parts[1];
						$key = str_replace("\n", "", $key);
						$key = str_replace("\r", "", $key);

						if ($this->has($key)) {

							switch ($this->map[$key]->type) {

								case ContainerMapEntry::TYPE_VALUE:
									$obj->$key = $this->map[$key]->value;
								break;

								case ContainerMapEntry::TYPE_CLASS:
									$obj->$key = $this->create($this->map[$key]->value, $this->map[$key]->arguments);
								break;

								case ContainerMapEntry::TYPE_SINGLETON:
									if (!$this->map[$key]->hasInstance()) {
										$obj->$key = $this->map[$key]->instance = 
											$this->create($this->map[$key]->value, $this->map[$key]->arguments);
									} else {
										$obj->$key = $this->map[$key]->instance;
									}
								break;

								default:
									throw new InvalidArgumentException(sprintf("'%s' is an invalid map key type", 
										strval($this->map[$key]->type)));

							}

						} else {
							throw new OutOfBoundsException(sprintf("DI DocComments injector cannot find '%s' in the map",
								strval($this->map[$key]->type)));
						}

					}

				}

			}

		}

		return $obj;

	}

	/**
	 * Check constructor doc comments for injections.
	 * 
	 * @param 	ReflectionClass 	$reflection 	Reflector.
	 * @param 	array 				$args 			Arguments to match.
	 * @return	array
	 * @throws 	InvalidArgumentException
	 */
	protected function checkConstructorDocComments(ReflectionClass $reflection, array $args): array
	{
		$method = $reflection->getConstructor();

		if (null === $method) {
			return $args;
		}

		$injectables = array();

		if ($doc = $method->getDocComment()) {

			$lines = explode("\n", $doc);

			foreach($lines as $line) {

				if (count($parts = explode("@Inject", $line)) > 1) {

					$parts = explode(" ", $parts[1]);

					if (count($parts) > 1) {

						$key = $parts[1];
						$key = str_replace("\n", "", $key);
						$key = str_replace("\r", "", $key);

						if (false === strpos($key, '|')) {
							throw new InvalidArgumentException(
								sprintf("Injectable constructor argument invalid, must be of formal 'num|def'. Got '%s'.", $key));
						}

						$final = explode('|', $key); 
						$injectables['arg-' . trim($final[0])] = trim($final[1]);

					}

				}

			}

		}

		print_r($injectables);

		return array();

	}

	/**
	 * Create an instance of something.
	 * 
	 * @param 	string 		$className 	Class to create.
	 * @param 	array|null	$args 		Arguments.
	 * @return	object
	 * @throws 	InvalidArgumentException
	 */
	public function create(string $className, ?array $args = null)
	{
		// Check class is in scope.
		if (!class_exists($className)) {
			throw new InvalidArgumentException(sprintf("Class '%s' not found or not in scope", $className));
		}

		// Create the reflection.
		$reflection = new ReflectionClass($className);

		// Check constructor arguments.
		$newArgs = $this->checkConstructorDocComments($reflection, $args);

		// Create the object.
		$obj = null;
		if (null === $args or 0 == count($args)) {
			$obj = new $className;
		} else {
			if (!is_array($args)) {
				$args = [$args];
			}
			$obj = $reflection->newInstanceArgs($args);
		}

		// Check the doc comments for injection.
		$this->checkClassDocComments($reflection, $obj);

		return $obj;
	}

	/**
	 * See if we have an instance of a class.
	 *
	 * @param 	string 	$key	Key to check.
	 *
	 * @return 	bool
	 */
	public function has(string $key) : bool
	{
		return array_key_exists($key, $this->map);
	}	

	/**
	 * Get an instance of a class.
	 *
	 * @param 	string 	$key	Key to get.
	 * @return 	mixed
	 *
	 * @throws 	OutOfBoundsException If the map key does not exist.
	 * @throws  InvalidArgumentException
	 */
	public function get(string $key)
	{
		if (!$this->has($key)) {
			throw new OutOfBoundsException(sprintf("DI has no '%s' in the map", $key));
		}
		if (ContainerMapEntry::TYPE_VALUE == $this->map[$key]->type) {
			return $this->map[$key]->value;
		} else if (ContainerMapEntry::TYPE_CLASS == $this->map[$key]->type) {
			return $this->create($this->map[$key]->value, $this->map[$key]->arguments);
		} else if (ContainerMapEntry::TYPE_SINGLETON == $this->map[$key]->type) {
			if (!$this->map[$key]->hasInstance()) {
				$this->map[$key]->instance = $this->create($this->map[$key]->value, $this->map[$key]->arguments);
			}
			return $this->map[$key]->instance;
		} else {
			throw new InvalidArgumentException(sprintf("Map entry for '%s' has an invalid type", $key));
		}
	}	

	/**
	 * Set something in the map.
	 * 
	 * @param 	string 						$key 		Key.
	 * @param 	ContainerMapEntryInterface	$item 		Item.
	 * @return 	ContainerInterface
	 */
	protected function set(string $key, ContainerMapEntryInterface $item): ContainerInterface
	{
		$this->map[$key] = $item;
		return $this;
	}

	/**
	 * Set a value.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @return 	ContainerInterface
	 */
	public function setValue(string $key, $value): ContainerInterface
	{
		return $this->set($key, new ContainerMapEntryValue($key, $value));
	}
	
	/**
	 * Set a class.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @param 	array|null	$args 	Arguments.
	 * @return 	ContainerInterface
	 */
	public function setClass(string $key, $value, ?array $args = null): ContainerInterface
	{
		return $this->set($key, new ContainerMapEntryClass($key, $value, $args));
	}

	/**
	 * Set a class and create it.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @param 	array|null	$args 	Arguments.
	 * @return 	object
	 */
	public function setClassAndCreate(string $key, $value, ?array $args = null)
	{
		$this->set($key, new ContainerMapEntryClass($key, $value, $args));
		return $this->create($this->map[$key]->value, $this->map[$key]->arguments);
	}

	/**
	 * Set a singleton.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @param 	array|null	$args 	Arguments.
	 * @return 	ContainerInterface
	 */
	public function setSingleton(string $key, $value, ?array $args = null): ContainerInterface
	{
		return $this->set($key, new ContainerMapEntrySingleton($key, $value, $args));
	}

	/**
	 * Set a singleton and create it.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @param 	array|null	$args 	Arguments.
	 * @return 	object
	 */
	public function setSingletonAndCreate(string $key, $value, ?array $args = null)
	{
		$this->set($key, new ContainerMapEntrySingleton($key, $value, $args));
		$this->map[$key]->instance = $this->create($this->map[$key]->value, $this->map[$key]->arguments);
		return $this->map[$key]->instance;
	}
}
