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
namespace GreenFedora\Http\Adr;

use GreenFedora\Application\Adr\AbstractAction;

use GreenFedora\Http\HttpRequestInterface;
use GreenFedora\Http\HttpResponseInterface;
use GreenFedora\DI\ContainerInterface;


/**
 * The base for HTTP actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractHttpAction extends AbstractAction 
{
	/**
	 * Input.
	 * @var HttpRequestInterface
	 */
	protected $request = null;

	/**
	 * Output.
	 * @var HttpResponseInterface
	 */
	protected $response = null;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	HttpRequestInterface		$request 	Input.
	 * @param 	HttpResponseInterface		$response 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, HttpRequestInterface $request, 
		HttpResponseInterface $response, array $params = [])
	{
        parent::__construct($container, $request, $response, $params);
	}

}