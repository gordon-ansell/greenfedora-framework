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
namespace GreenFedora\Logger\Formatter;

use GreenFedora\Logger\Formatter\LogFormatterInterface;
use GreenFedora\Logger\LogLevel;
use GreenFedora\Arr\Arr;

/**
 * Format message for a log file in some sort of standard way.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class StdLogFormatter implements LogFormatterInterface
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
	 * Constructor.
	 *
	 * @param 	iterable			$cfg 		Configs.
	 *
	 * @return	void
	 */
	public function __construct(iterable $cfg)	
	{
		$this->cfg = new Arr($this->defaults);
		$this->cfg = $this->cfg->mergeReplaceRecursive($cfg);
	}
	
	/**
	 * Format the message.
	 *
	 * @param 	string 				$msg 		Message to format.
	 * @param 	string 				$level 		Level of message.
	 * @param 	array 				$context	Message context.
	 *
	 * @return 	void
	 */	
	public function format(string $msg, string $level, array $context = array()) : string
	{
		$dt = new \DateTime();
		$ret =	sprintf("%s %s %s", 
			$dt->format($this->cfg->dtFormat),
			strtoupper(str_pad($level, 10)),
			$msg
		);
		return $ret;		
	}
}