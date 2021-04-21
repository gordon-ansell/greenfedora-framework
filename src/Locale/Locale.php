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
use GreenFedora\Arr\Arr;

/**
 * Locale object.
 *
 * Things like timezones and currencies.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Locale implements LocaleInterface
{
	/**
	 * Configs for locale.
	 * @var array
	 */
	protected $defaults = array(
		'timezone'			=>	'UTC',
		'currency'			=>	'GBP',
		'currencySymbol'	=>	'£',
		'lang'				=>	'en',
		'langFull'			=>	'en_GB',
	);
	
	/**
	 * Configs.
	 * @var Arr
	 */
	protected $cfg = null;	
		
	/**
	 * Constructor.
	 *
	 * @param 	iterable|null	$cfg 		Locale configs.
	 *
	 * @return 	void
	 */
	public function __construct(?iterable $config_locale = null)
	{
		$this->cfg = new Arr();
		$this->cfg->timezone = date_default_timezone_get();
		$this->cfg = $this->cfg->mergeReplaceRecursive($config_locale);
		$this->setTimezone($this->cfg->timezone);
	}
	
	/**
	 * Set the timezone.
	 *
	 * @param 	string 		$tz 		Timesone to set.
	 * @param 	bool 		$system		Set it in the system too?
	 *
	 * @return 	string 		Old timezone.
	 */
	public function setTimezone(string $tz, bool $system = true) : string
	{
		$saved = $this->cfg->timezone;
		$this->cfg->timezone = $tz;	
		
		if ($system) {
			date_default_timezone_set($this->cfg->timezone);
		}
		
		return $saved;
	}	
	
	/**
	 * Get the timezone.
	 *
	 * @return	string
	 */
	public function getTimezone() : string
	{
		return $this->cfg->timezone;
	}	

	/**
	 * Get the language.
	 *
	 * @param 	bool 		$full		Full language?
	 *
	 * @return	string
	 */
	public function getLangCode(bool $full = true) : string
	{
		return ($full) ? $this->cfg->langFull : $this->cfg->lang;
	}	
}
