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
use GreenFedora\Arr\Arr;

use League\Plates\Engine;

/**
 * Plates templates.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class PlatesTemplate extends Engine implements TemplateInterface
{
	/**
	 * Configs.
	 * @var ArrInterface
	 */
	protected $cfg = null;	 

	/**
	 * Constructor.
	 *
     * @param   iterable     		$cfg            Template specifications.
	 *
	 * @return 	void
	 */
	public function __construct(iterable $cfg)
	{
		$this->cfg = new Arr($cfg);
        parent::__construct($this->cfg->templateDir);      
	}	

}
