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
namespace GreenFedora\Logger\Writer;

use GreenFedora\Logger\Formatter\LogFormatterInterface;
use GreenFedora\Logger\LogLevel;
use GreenFedora\Arr\Arr;

/**
 * Abstract log writer.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 * @Inject logFormatter
 */

abstract class AbstractLogWriter
{
	/**
	 * Configs from the logger.
	 * @var array
	 */
	protected $defaults = array(
		'level'			=>	LogLevel::NOTICE,
		'dtFormat'		=>	'Y-m-d H:i:s.v',
		'retainDays'	=>	30,				
	);	
	
	/**
	 * Configs.
	 * @var Arr
	 */
	protected $cfg = null;	
	
	/**
	 * Log formatter.
	 * @var LogFormatterInterface
	 */
	protected $formatter = null;	

	/**
	 * Constructor.
	 *
	 * @param 	iterable				$cfg 		Configs.
	 * @param 	LogFormatterInterface	$formatter	Log message formatter.
	 *
	 * @return	void
	 */
	public function __construct(iterable $cfg, ?LogFormatterInterface $formatter = null)	
	{
		$this->cfg = new Arr($this->defaults);
		$this->cfg = $this->cfg->mergeReplaceRecursive($cfg);
		if (null === $formatter) {
			$formatter = $this->logFormatter;
		}
		$this->formatter = $formatter;
	}
}