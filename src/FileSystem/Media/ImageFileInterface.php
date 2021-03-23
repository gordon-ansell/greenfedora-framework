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
namespace GreenFedora\FileSystem\Media;

use GreenFedora\FileSystem\Media\Exception\OutOfBoundsException;
use GreenFedora\FileSystem\Media\Exception\RuntimeException;
use GreenFedora\FileSystem\FileInfo;
use GreenFedora\Uri\Uri;
use GreenFedora\Uri\UriInterface;
use GreenFedora\Bitset\Bitset;
use GreenFedora\Bitset\BitsetInterface;
use GreenFedora\Link\Link;

/**
 * Image file object interface.
 *
 * With lots of fancy processing.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
interface ImageFileInterface
{
	/**
	 * Add a sub-image.
	 *
	 * @param 	string 		$tag 		Sub-image key.
	 * @param 	string 		$path		Sub-image path.
	 * @param	array|null	$attribs	Sub-image attributes.
	 *
	 * @return 	ImageFileInterface
	 */
	public function addSubImage(string $key, string $path, ?array $attribs = null) : ImageFileInterface;
	
	/**
	 * Get a sub-image.
	 *
	 * @param	string		$key		Sub image key.
	 * @param 	bool 		$suppress	Supress exception?
	 *
	 * @return 	ImageFileInterace|null
	 *
	 * @throws	OutOfBoundsException 	If sub-image not found.
	 */
	public function getSubImage(string $key, bool $suppress = false) : ?ImageFileInterface;

	/**
	 * Get an attribute.
	 *
	 * @param	string		$key		Attribute key.
	 * @param 	bool 		$suppress	Supress exception?
	 *
	 * @return 	mixed
	 *
	 * @throws	OutOfBoundsException 	If attribute not found.
	 */
	public function getAttrib(string $key, bool $suppress = false);
	
	/**
	 * Get the URL for this image.
	 *
	 * @param 	string|null 		$baseUrl 	Base URL.
	 *
	 * @return 	string
	 */
	public function getUrl(?string $baseUrl = null) : string;
	
	/**
	 * Get the image HTML.
	 *
	 * @param 	array|null			$attribs	Additional attribs if necessary.
	 * @param 	string|null 		$baseUrl 	Base URL.
	 *
	 * @return 	string
	 */
	public function getHtml(?array $attribs = null, ?string $baseUrl = null) : string;
}
