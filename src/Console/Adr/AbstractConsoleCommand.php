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
	protected $help = [
		'name'			=>	'',
		'description'	=>	'',
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
		$this->init();
	}

	/**
	 * Initialise stuff.
	 * 
	 * @return void
	 */
	protected function init()
	{

	}

	/**
	 * Set the help name.
	 * 
	 * @param 	string 	$name 	Name to set.
	 * @return 	ConsoleCommandInterface
	 */
	public function setName(string $name): ConsoleCommandInterface
	{
		$this->help['name'] = $name;
		return $this;
	}

	/**
	 * Set the help description.
	 * 
	 * @param 	string 	$desc 	Description to set.
	 * @return 	ConsoleCommandInterface
	 */
	public function setDescription(string $desc): ConsoleCommandInterface
	{
		$this->help['description'] = $desc;
		return $this;
	}
}