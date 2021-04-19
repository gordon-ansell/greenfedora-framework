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
namespace GreenFedora\Inflector;

/**
 * Inflector interface.
 */	
interface InflectorInterface
{	 	
	/**
	 * Slugify.
	 *
	 * @param 	string		$data 		String to slugify.
	 *
	 * @return 	string
	 */
	public function slugify(string $data) : string;
		
    /**
     * Title case.
     *
     * @param   string       $data           Data to filter.
     *
     * @return  string
     */
    public function titleCase(string $data) : string;

	/**
	 * Sha.
	 *
	 * @param 	string		$data 		String to enode.
	 *
	 * @return 	string
	 */
	public function sha(string $data) : string;

	/**
	 * Strip script tags.
	 *
	 * @param 	string		$data 		String to strip.
	 *
	 * @return 	string
	 */
	public function stripScriptTags(string $data) : string;

	/**
	 * Strip all tags.
	 *
	 * @param 	string		$data 		String to strip.
	 *
	 * @return 	string
	 */
	public function stripAllTags(string $data) : string;

	/**
	 * Strip tags and their content.
	 *
	 * @param 	string		$data 		String to strip.
	 * @param 	string		$tags 		Tags to include.
	 * @param 	bool		$invert 	Invert processing.	
	 *
	 * @return 	string
	 */
	public function stripTagsContent(string $data, string $tags = '', bool $invert = false) : string;

	/**
	 * Shorten a string.
	 *
	 * @param 	string		$data 		String to shorten.
	 * @param 	int			$words 		Number of words.
	 * @param 	string		$suff 		Suffix.	
	 *
	 * @return 	string
	 */
	public function shortenStringWords(string $data, int $words = 100, string $suff = ' ...') : string;
}
