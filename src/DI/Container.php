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

use GreenFedora\DI\Map\ContainerMapEntry;
use GreenFedora\DI\Map\ContainerMapEntryClass;
use GreenFedora\DI\Map\ContainerMapEntrySingleton;
use GreenFedora\DI\Map\ContainerMapEntryValue;
use GreenFedora\DI\Map\ContainerMapEntryInterface;

use \ReflectionClass;
use \ReflectionNamedType;
use \ReflectionType;

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
	 * Get the map.
	 * 
	 * @return	ContainerMapEntryInterface[] 
	 */
	public function getMap(): ?array
	{
		return $this->map;
	}

	/**
	 * Create by the type.
	 * 
	 * @param 	string 		$key 	Key of item to create.
	 * @return 	mixed|object|null
	 * @throws 	InvalidArgumentException
	 * @throws  OutOfBoundsException
	 */
	protected function createByType(string $key)
	{
		if ($this->has($key)) {

			switch ($this->map[$key]->type) {

				case ContainerMapEntry::TYPE_VALUE:
					return $this->map[$key]->value;
				break;

				case ContainerMapEntry::TYPE_CLASS:
					return $this->create($this->map[$key]->value, $this->map[$key]->arguments);
				break;

				case ContainerMapEntry::TYPE_SINGLETON:
					if (!$this->map[$key]->hasInstance()) {
						$this->map[$key]->instance = $this->create($this->map[$key]->value, $this->map[$key]->arguments);
					} 
					return $this->map[$key]->instance;
				break;

				default:
					throw new InvalidArgumentException(sprintf("DI: '%s' is an invalid map key type, for key '%s'", 
						strval($this->map[$key]->type), $key));

			}

		} else {
			throw new OutOfBoundsException(sprintf("DI: createByType cannot find '%s' in the map", $key));
		}

		return null;
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

						$obj->$key = $this->createByType($key);

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
	 * @param 	array|null 			$args 			Arguments to match.
	 * @return	array|null
	 * @throws 	InvalidArgumentException
	 */
	protected function checkConstructorDocComments(ReflectionClass $reflection, ?array $args): ?array
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

		if (count($injectables) > 0) {
			$newArgs = array();
			$paramSpec = $method->getParameters();
			$count = 0;

			foreach ($paramSpec as $p) {
				if ($args[$count] and null !== $args[$count]) {
					$newArgs[] = $args[$count];
				} else if (array_key_exists('arg-' . $count, $injectables)) {
					$newArgs[] = $this->createByType($injectables['arg-' . $count]);
				} else {
					$newArgs[] = null;
				}
				$count++;
			}

			return $newArgs;

		} else {
			return $args;
		}

		return array();

	}

	/**
	 * Find entry by the class name it contains (which is in the 'value' field).
	 * 
	 * @param 	mixed 	$name 		Name to find.
	 * @return 	string|null 		Key name or null.
	 */
	protected function findEntryByValue($name): ?string
	{
		foreach ($this->map as $k => $v) {
			if ($v->isInjectable() and $v->valueMatches($name)) {
				return $k;
			}
		}
		return null;
	}

	/**
	 * Find a value by its key.
	 * 
	 * @param 	mixed 	$name 		Name to find.
	 * @return 	string|null 		Key name or null.
	 */
	protected function findValueByKey($name): ?string
	{
		foreach ($this->map as $k => $v) {
			if ($v->isInjectable() and $v->type == ContainerMapEntry::TYPE_VALUE and $v->valueMatches($name)) {
				return $k;
			}
		}
		return null;
	}

	/**
	 * Possibly inject constructor parameters.
	 * 
	 * @param 	ReflectionClass 	$reflection 	Class we're dealing with.
	 * @param 	array|null 			$args 			Real arguments.
	 * @return 	array|null 
	 */
	protected function possiblyInjectConstructorParameters(ReflectionClass $reflection, ?array $args = null): ?array
	{
		echo "Dealing with class: " . $reflection->getName() . '<br />' .PHP_EOL;
		echo "Args passed: " . '<br />' . PHP_EOL;
		print_r($args);
		echo '<br />' .PHP_EOL;


		$method = $reflection->getConstructor();

		if (null === $method) {
			return $args;
		}

		$parameters = $method->getParameters();

		$newArgs = [];

		$count = 0;
		foreach ($parameters as $p) {
			echo "Count : " . $count . '<br />' . PHP_EOL;
			if (is_array($args) and (count($args) > $count) and !is_null($args[$count])) {
				$newArgs[] = $args[$count];
				echo "Using passed argument" . '<br />' . PHP_EOL;
			} else {
				$reflectionType = $p->getType();
				$found = null;
				if (!is_null($reflectionType) and ($reflectionType instanceof ReflectionNamedType)) {
					$type = $reflectionType->getName();
					if (!is_null($type) and !$reflectionType->isBuiltIn()) {
						$found = $this->findEntryByValue($type);
					} else {
						$found = $this->findValueBykey($p->getName());
					}
				}
				if (!is_null($found)) {
					$newArgs[] = $this->createByType($found);
					echo "Using injection" . '<br />' . PHP_EOL;
				} else if (is_array($args) and (count($args) > $count)) {
					$newArgs[] = $args[$count];
					echo "Using passed argument (2)" . '<br />' . PHP_EOL;
				} else if ($p->isDefaultValueAvailable()) {
					$newArgs[] = $p->getDefaultValue();
					echo "Using default value" . '<br />' . PHP_EOL;
				} else {
					$newArgs[] = null;
					echo "Using null" . '<br />' . PHP_EOL;
				}
			}
			$count++;
		}

		return $newArgs;

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
		$args = $this->possiblyInjectConstructorParameters($reflection, $args);
		//$args = $this->checkConstructorDocComments($reflection, $args);

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
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setValue(string $key, $value, bool $injectable = true): ContainerInterface
	{
		return $this->set($key, new ContainerMapEntryValue($key, $value, $injectable));
	}
	
	/**
	 * Set a class.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setClass(string $key, $value, $args = null, bool $injectable = true): ContainerInterface
	{
		if (!is_array($args) and !is_null($args)) {
			$args = [$args];
		}
		return $this->set($key, new ContainerMapEntryClass($key, $value, $args, $injectable));
	}

	/**
	 * Set a class and create it.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	object
	 */
	public function setClassAndCreate(string $key, $value, $args = null, bool $injectable = true)
	{
		$this->setClass($key, $value, $args, $injectable);
		return $this->create($this->map[$key]->value, $this->map[$key]->arguments);
	}

	/**
	 * Set a singleton.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setSingleton(string $key, $value, $args = null, bool $injectable = true): ContainerInterface
	{
		if (!is_array($args) and !is_null($args)) {
			$args = [$args];
		}
		return $this->set($key, new ContainerMapEntrySingleton($key, $value, $args, $injectable));
	}

	/**
	 * Set a singleton and create it.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	object
	 */
	public function setSingletonAndCreate(string $key, $value, $args = null, bool $injectable = true)
	{
		$this->setSingleton($key, $value, $args);
		$this->map[$key]->instance = $this->create($this->map[$key]->value, $this->map[$key]->arguments);
		return $this->map[$key]->instance;
	}
}
