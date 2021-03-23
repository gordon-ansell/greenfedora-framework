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
namespace GreenFedora\Lang;

use GreenFedora\Lang\LangInterface;

/**
 * For objects aware of the language object.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

trait LangAwareTrait
{
	/**
	 * Get the language object.
	 *
	 * @return	LangInterface
	 *
	 */ 
	abstract public function getLang() : LangInterface;

    /**
	 * Translate a language string.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	... 		$args 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function x(string $str, ...$args) : string
	{
		$args = func_get_args();
		array_shift($args);
		return $this->getLang()->translateArray($str, $args);
	}  
	
    /**
	 * Translate a language string with relevant pluralisation.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	int 		$pluralCount	Count for pluralisation.
	 * @param 	... 		$vars 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function xp(string $str, int $pluralCount = 1, ...$args) : string
	{
		$args = func_get_args();
		array_shift($args);
		array_shift($args);
		return $this->getLang()->translatePluralisedArray($str, $pluralCount, $args);
	}   
}
