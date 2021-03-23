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
 * Inflector.
 */	
class Inflector implements InflectorInterface
{	 	
	/**
	 * Slugify.
	 *
	 * @param 	string		$data 		String to slugify.
	 *
	 * @return 	string
	 */
	public function slugify(string $data) : string
	{
	  	// Replace non letter or digits by -
	  	$data = preg_replace('~[^\pL\d]+~u', '-', $data);
	
	  	// Transliterate.
	  	$data = iconv('utf-8', 'us-ascii//TRANSLIT', $data);
	
	  	// Remove unwanted characters.
	  	$data = preg_replace('~[^-\w]+~', '', $data);
	
	  	// Trim.
	  	$data = trim($data, '-');
	
	  	// Remove duplicate -
	  	$data = preg_replace('~-+~', '-', $data);
	
	  	// Lowercase.
	  	$data = strtolower($data);

		return $data;		
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
		//remove HTML, storing it for later
		//       HTML elements to ignore    | tags  | entities
		$regx = '/<(code|var)[^>]*>.*?<\/\1>|<[^>]+>|&\S+;/';
		preg_match_all($regx, $data, $html, PREG_OFFSET_CAPTURE);
		$data = preg_replace($regx, '', $data);
	
		//find each word (including punctuation attached)
		preg_match_all('/[\w\p{L}&`\'‘’"“\.@:\/\{\(\[<>_]+-? */u', $data, $m1, PREG_OFFSET_CAPTURE);
	
		foreach ($m1[0] as &$m2) {
			//shorthand these- "match" and "index"
			list ($m, $i) = $m2;
		
			//correct offsets for multi-byte characters (`PREG_OFFSET_CAPTURE` returns *byte*-offset)
			//we fix this by recounting the text before the offset using multi-byte aware `strlen`
			$i = mb_strlen(substr ($data, 0, $i), 'UTF-8');
		
			//find words that should always be lowercase…
			//(never on the first word, and never if preceded by a colon)
			if ($i>0 and mb_substr ($data, max (0, $i-2), 1, 'UTF-8') !== ':' and 
				!preg_match ('/[\x{2014}\x{2013}] ?/u', mb_substr ($data, max (0, $i-2), 2, 'UTF-8')) and 
				preg_match ('/^(a(nd?|s|t)?|b(ut|y)|en|for|i[fn]|o[fnr]|t(he|o)|vs?\.?|via)[ \-]/i', $m)) {
					
				//…and convert them to lowercase
				$m = mb_strtolower($m, 'UTF-8');
				
			//else:	brackets and other wrappers
			} else if (preg_match('/[\'"_{(\[‘“]/u', mb_substr($data, max (0, $i-1), 3, 'UTF-8'))) {
				
				//convert first letter within wrapper to uppercase
				$m = mb_substr($m, 0, 1, 'UTF-8') . mb_strtoupper(mb_substr($m, 1, 1, 'UTF-8'), 'UTF-8') . mb_substr($m, 2, mb_strlen($m, 'UTF-8')-2, 'UTF-8');
			
			//else:	do not uppercase these cases
			} else if (!preg_match ('/[\])}]/', mb_substr($data, max (0, $i-1), 3, 'UTF-8')) and 
				!preg_match('/[A-Z]+|&|\w+[._]\w+/u', mb_substr($m, 1, mb_strlen($m, 'UTF-8')-1, 'UTF-8'))) {
				
				$m = mb_strtoupper(mb_substr($m, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($m, 1, mb_strlen($m, 'UTF-8'), 'UTF-8');
			}
		
			//resplice the title with the change (`substr_replace` is not multi-byte aware)
			$data = mb_substr($data, 0, $i, 'UTF-8') . $m . mb_substr($data, $i+mb_strlen($m, 'UTF-8'), mb_strlen($data, 'UTF-8'), 'UTF-8');
		}
	
		//restore the HTML
		foreach ($html[0] as &$tag) {
			$data = substr_replace ($data, $tag[0], $tag[1], 0);
		}
	
		return $data;
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
		return sha1($data);
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
    	return preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $data);
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
	    return strip_tags($this->stripScriptTags($data));
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
	  	preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
	  	$tags = array_unique($tags[1]); 
	    
	  	if (is_array($tags) AND count($tags) > 0) { 
	    	if ($invert == false) { 
				$data = preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $data); 
	    	} else { 
				$data = preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $data); 
	    	} 
	  	} else if ($invert == false) { 
	    	$data = preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $data); 
	  	} 
	  	return $data; 
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
		$array = explode(" ", $data);
		
		//  Already short enough, return the whole thing.
		if (count($array) > $words) {
			array_splice($array, $words);
			$data = implode(" ", $array) . $suff;
		}
		
		return $data;
	}
}
