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
namespace GreenFedora\Template;

use GreenFedora\Template\TemplateInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;
use GreenFedora\Inflector\InflectorInterface;
use GreenFedora\Inflector\InflectorAwareInterface;
use GreenFedora\Inflector\InflectorAwareTrait;

use League\Plates\Engine;

/**
 * Plates templates.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class PlatesTemplate extends Engine implements TemplateInterface, ContainerAwareInterface, InflectorAwareInterface
{
	use ContainerAwareTrait;
	use InflectorAwareTrait;

	/**
	 * Configs.
	 * @var Arr
	 */
	protected $cfg = null;	 

	/**
	 * Constructor.
	 *
     * @param   iterable     		$cfg            Template specifications.
	 * @param 	ContainerInterface	$container		Dependency injection container.
     * @param 	string|array		$templateDir 	Template directory.
	 *
	 * @return 	void
	 */
	public function __construct(iterable $cfg, ContainerInterface $container)
	{
		$this->cfg = new Arr($cfg);
        parent::__construct($this->cfg->templateDir);      
        $this->container = $container;
	}	
}
