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

use GreenFedora\Inflector\InflectorInterface;

/**
 * For things aware of the inflector.
 */	
trait InflectorAwareTrait
{	 	
	/**
	 * Get the inflector.
	 *
	 * @return	InflectorInterface
	 */
	abstract public function getInflector() : InflectorInterface;

	/**
	 * Slugify.
	 *
	 * @param 	string		$data 		String to slugify.
	 *
	 * @return 	string
	 */
	public function slugify(string $data) : string
	{
		return $this->getInflector()->slugify($data);
	}		
		
    /**
     * Title case.
     *
     * @param   string       $data           Data to filter.
     *
     * @return  string
     */
    public function titleCase(string $data) : string
    {	
		return $this->getInflector()->titleCase($data);
    }

	/**
	 * Sha.
	 *
	 * @param 	string		$data 		String to endode.
	 *
	 * @return 	string
	 */
	public function sha(string $data) : string
	{
		return $this->getInflector()->sha($data);
	}		

	/**
	 * Strip script tags.
	 *
	 * @param 	string		$data 		String to strip.
	 *
	 * @return 	string
	 */
	public function stripScriptTags(string $data) : string
	{
		return $this->getInflector()->stripScriptTags($data);
	}		

	/**
	 * Strip all tags.
	 *
	 * @param 	string		$data 		String to strip.
	 *
	 * @return 	string
	 */
	public function stripAllTags(string $data) : string
	{
		return $this->getInflector()->stripAllTags($data);
	}		

	/**
	 * Strip tags and their content.
	 *
	 * @param 	string		$data 		String to strip.
	 * @param 	string		$tags 		Tags to include.
	 * @param 	bool		$invert 	Invert processing.	
	 *
	 * @return 	string
	 */
	public function stripTagsContent(string $data, string $tags = '', bool $invert = false) : string
	{
		return $this->getInflector()->stripTagsContent($data, $tags, $invert);
	}		

	/**
	 * Shorten a string.
	 *
	 * @param 	string		$data 		String to shorten.
	 * @param 	int			$words 		Number of words.
	 * @param 	string		$suff 		Suffix.	
	 *
	 * @return 	string
	 */
	public function shortenStringWords(string $data, int $words = 100, string $suff = ' ...') : string
	{
		return $this->getInflector()->shortenStringWords($data, $words, $suff);
	}
}
