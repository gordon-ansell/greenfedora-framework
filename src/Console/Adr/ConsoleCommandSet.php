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

use GreenFedora\DI\ContainerAwareTrait;
use GreenFedora\DI\ContainerAwareInterface;
use GreenFedora\DI\ContainerInterface;
use GreenFedora\Console\Adr\ConsoleCommandSetInterface;
use GreenFedora\Console\ConsoleRequestInterface;
use GreenFedora\Console\ConsoleResponseInterface;

/**
 * The base for console actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleCommandSet implements ContainerAwareInterface, ConsoleCommandSetInterface
{
    use ContainerAwareTrait;

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
     * Commands.
     * @var array
     */
    protected $commands = [];

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	ConsoleRequestInterface		$request 	Input.
	 * @param 	ConsoleResponseInterface	$response 	Output.
	 * @param 	array		                $commands 	Array of commands.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, ConsoleRequestInterface $request, 
        ConsoleResponseInterface $response, ?array $commands = null) 
	{
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
	}

    /**
     * Add a command.
     * 
     * @param   string  $name   Command name.
     * @param   string  $class  Command class.
     * @return  ConsoleCommandSetInterface 
     */
    public function addCommand(string $name, string $class): ConsoleCommandSetInterface
    {
        $this->commands[$name] = $class;
        $this->getContainer()->setSingletonAndCreate('command_' . $name, $class, 
            [$this->container, $this->request, $this->response]);
        return $this;
    }

}