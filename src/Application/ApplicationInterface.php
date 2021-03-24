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
namespace GreenFedora\Application;

use GreenFedora\Logger\LoggerInterface;
use GreenFedora\Locale\LocaleInterface;
use GreenFedora\Lang\LangInterface;
use GreenFedora\Inflector\InflectorInterface;

/**
 * Application interface.
 *
 * All applications must implement this interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ApplicationInterface
{
	/**
	 * See if we have a config key.
	 *
	 * @param	string			$key		Key to check.
	 * 
	 * @return 	bool
	 */
	public function hasConfig(string $key) : bool;

	/**
	 * Get the config.
	 *
	 * @param	string|null		$key		Key to get or null to get them all.
	 * @param 	mixed 			$default	Default value to return if key not found.
	 * 
	 * @return 	mixed
	 */
	public function getConfig(?string $key = null, $default = null);

	/**
	 * Get the locale.
	 *
	 * @return	LocaleInterface
	 */
	public function getLocale() : LocaleInterface;

	/**
	 * Get the logger.
	 *
	 * @return	LoggerInterface
	 */
	public function getLogger() : LoggerInterface;

	/**
	 * Get the language processor.
	 *
	 * @return	LangInterface
	 */
	public function getLang() : LangInterface;

	/**
	 * Get the inflector.
	 *
	 * @return	InflectorInterface
	 */
	public function getInflector() : InflectorInterface;
}