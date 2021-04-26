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
namespace GreenFedora\Console\Adr;

use GreenFedora\Application\Adr\AbstractAction;
use GreenFedora\Console\ConsoleRequestInterface;
use GreenFedora\Console\ConsoleResponseInterface;
use GreenFedora\Console\Adr\ConsoleCommandInterface;
use GreenFedora\DI\ContainerInterface;

/**
 * The base for console actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractConsoleCommand extends AbstractAction implements ConsoleCommandInterface
{
	/**
	 * Input.
	 * @var ConsoleRequestInterface
	 */
	protected $request = null;

	/**
	 * Output.
	 * @var ConsoleResponseInterface
	 */
	protected $response = null;

	/**
	 * Help.
	 * @var array
	 */
	protected static $help = [
		'name' => '',
		'description' => '',
	];

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	ConsoleRequestInterface		$request 	Input.
	 * @param 	ConsoleResponseInterface	$response 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, ConsoleRequestInterface $request, 
		ConsoleResponseInterface $response, array $params = [])
	{
        parent::__construct($container, $request, $response, $params);
	}

	/**
	 * Get the help.
	 * 
	 * @return 	array
	 */
	public static function getHelp(): array
	{
		return static::$help;
	}

	/**
	 * Do the auto help.
	 * 
	 * @param 	array 	$classes 	Classes to process.
	 * @return 	array
	 */
	public function autoHelp(array $classes): array
	{
		$ret = [];
		foreach ($classes as $c) {
			$tmp = $c::getHelp();
			if (array_key_exists('detail', $tmp)) {
				$ret[$tmp['name']] = [$tmp['description'], $tmp['detail']];
			} else {
				$ret[$tmp['name']] = $tmp['description'];
			}
		}
		return $ret;
	}

}