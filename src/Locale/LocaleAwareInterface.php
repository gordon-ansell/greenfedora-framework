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
namespace GreenFedora\Locale;

use GreenFedora\Locale\LocaleInterface;

/**
 * Objects that are aware of the locale object.
 *
 * Things like timezones and currencies.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface LocaleAwareInterface
{
	/**
	 * Get the locale.
	 *
	 * @return	LocaleInterface
	 */
	public function getLocale() : LocaleInterface;

	/**
	 * Set the timezone.
	 *
	 * @param 	string 		$tz 		Timesone to set.
	 * @param 	bool 		$system		Set it in the system too?
	 *
	 * @return 	string 		Old timezone.
	 */
	public function setTimezone(string $tz, bool $system = true) : string;
	
	/**
	 * Get the timezone.
	 *
	 * @return	string
	 */
	public function getTimezone() : string;

	/**
	 * Get the language.
	 *
	 * @param 	bool 		$full		Full language?
	 *
	 * @return	string
	 */
	public function getLangCode(bool $full = true) : string;
}
