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
namespace GreenFedora\Table;

/**
 * Table maker.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Table implements TableInterface
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
     * @param   bool                $init           Initiate?
	 *
	 * @return 	void
	 */
	public function __construct(iterable $cfg, bool $init = true)
	{
		$this->cfg = new Arr($cfg);

        if ($init) {
            $this->init();
        }
	}	
}
