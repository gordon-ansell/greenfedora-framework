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

/**
 * Interface for the language object.
 *
 * Translations and such.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface LangInterface
{
    /**
     * Load a set of language translations.
     *
     * @param   string|null                 $lang       Language.
     * @param   iterable|null      			$paths      Paths to scan for configs.
	 *
     * @return  void
     */
    public function load(?string $lang = null, ?iterable $paths = null);

    /**
	 * Translate a language string, passing in an array.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	array 		$args 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translateArray(string $str, array $args = array()) : string;

    /**
	 * Translate a language string.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	... 		$args 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translate(string $str, ...$args) : string;
	
    /**
	 * Translate a language string with relevant pluralisation, passing in an array.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	int 		$pluralCount	Count for pluralisation.
	 * @param 	array 		$vars 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translatePluralisedArray(string $str, int $pluralCount = 1, array $args = array()) : string;

    /**
	 * Translate a language string with relevant pluralisation.
	 *
	 * @param 	string		$str 			String to translate.
	 * @param 	int 		$pluralCount	Count for pluralisation.
	 * @param 	... 		$vars 			Vars for formatting.
	 *
	 * @return 	string
	 */
	public function translatePluralised(string $str, int $pluralCount = 1, ...$args) : string;
}
