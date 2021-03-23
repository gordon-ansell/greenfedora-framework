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
use GreenFedora\FileSystem\DirIter;
use GreenFedora\FileSystem\Media\ImageFileInterface;
use GreenFedora\Uri\Uri;
use GreenFedora\Uri\UriInterface;
use GreenFedora\Bitset\Bitset;
use GreenFedora\Bitset\BitsetInterface;
use GreenFedora\Link\Link;

/**
 * Image file object.
 *
 * With lots of fancy processing.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class ImageFile extends FileInfo implements ImageFileInterface
{
	/**
	 * Flags.
	 * @var int
	 */
	const NO_DEFAULT_LINK_SELF	=	1;
	const NO_TITLE_IS_ALT		=	2;
	const NO_ALT_IS_TITLE		=	4;
	const NO_ALT_USE_CAPTION 	=	8;
	const NO_TITLE_USE_CAPTION	=	16;
	const NO_AUTO_SIZES_TAG		=	32;
	
	/**
	 * The URI for the image file.
	 * @var Uri
	 */
	protected $uri = null;	
	 	
	/**
	 * Image attributes.
	 * @var array
	 */
	protected $attribs = array();	
	
	/**
	 * Source set.
	 * @var mixed
	 */
	protected $srcset = null;	
	
	/**
	 * Sub images that are part of the srcset.
	 * @var ImageFile[]
	 */
	protected $subImages = array();	
	
	/**
	 * Flags.
	 * @var BitsetInterface
	 */
	protected $flags = null;	
	
	/**
	 * Base Url.
	 * @var string|null
	 */
	protected $baseUrl = null;	
	
	/**
	 * Is this a sub-image?
	 * @var bool
	 */
	protected $isSub = false;	

	/**
	 * Constructor.
	 *
	 * @param 	string|UriInterface		$path		Image path to process.
	 * @param 	array					$attribs	Attributes.
	 * @param 	string|null				$baseUrl	Base URL.
	 * @param 	string|null				$basePath	Base path.
	 * @param 	mixed 					$srcset		Null means autodetect, false means no, true means yes, array means yes with these sizes.	
	 * @param	int						$flags		Flags.
	 * @param 	bool 					$isSub		Is this a sub-image?
	 *
	 * @return 	void
	 */
	public function __construct($path, array $attribs = array(), ?string $baseUrl = null, ?string $basePath = null, $srcset = null, int $flags = 0, bool $isSub = false)
	{
		if ($path instanceof UriInterface) {
			$this->uri = $path;
		} else {
			$this->uri = new Uri($path);
		}

		if (null !== $basePath) {
			$this->uri->setBaseUri($basePath);
		}
		
		$this->attribs = $attribs;
		
		parent::__construct($this->uri->getAbsolute());

		if (null !== $baseUrl) {
			$this->baseUrl = $baseUrl;
		}		
		
		$this->srcset = $srcset;
		$this->flags = new BitSet($flags);
		$this->isSub = $isSub;
		
		$this->process();
	}	
	
	/**
	 * Add a sub-image.
	 *
	 * @param 	string 		$tag 		Sub-image key.
	 * @param 	string 		$path		Sub-image path.
	 * @param	array|null	$attribs	Sub-image attributes.
	 *
	 * @return 	ImageFileInterface
	 */
	public function addSubImage(string $key, string $path, ?array $attribs = null) : ImageFileInterface
	{
		if (null === $attribs) {
			$attribs = $this->attribs;
		}
		$this->subImages[$key] = new self($path, $attribs, $this->baseUrl, $this->uri->getBaseUri(), null, $this->flags->getFlags(), true);
		return $this;
	}	
	
	/**
	 * Get a sub-image.
	 *
	 * @param	string		$key		Sub image key.
	 * @param 	bool 		$suppress	Supress exception?
	 *
	 * @return 	ImageFileInterface|null
	 *
	 * @throws	OutOfBoundsException 	If sub-image not found.
	 */
	public function getSubImage(string $key, bool $suppress = false) : ?ImageFileInterface
	{
		if (array_key_exists($key, $this->subImages)) {
			return $this->subImages[$key];
		}
		if (!$suppress) {
			throw new OutOfBoundsException(sprintf("Sub-image with key '%s' not found", $key));
		}
		return null;
	}

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
	public function getAttrib(string $key, bool $suppress = false)
	{
		if (array_key_exists($key, $this->attribs)) {
			return $this->attribs[$key];
		}
		if (!$suppress) {
			throw new OutOfBoundsException(sprintf("Attribute with key '%s' not found", $key));
		}
		return null;
	}
	
	/**
	 * Get the URL for this image.
	 *
	 * @param 	string|null 		$baseUrl 	Base URL.
	 *
	 * @return 	string
	 */
	public function getUrl(?string $baseUrl = null) : string
	{
		$baseUrl = (null === $baseUrl) ? $this->baseUrl : $baseUrl;
		if (null === $baseUrl) {
			throw new RuntimeException("Image's baseUrl is null.");
		}
		return str_replace(DS, US, str_replace($this->uri->getBaseUri(), $baseUrl, $this->getPathname()));
	}	
	
	/**
	 * Get the image HTML.
	 *
	 * @param 	array|null			$attribs	Additional attribs if necessary.
	 * @param 	string|null 		$baseUrl 	Base URL.
	 *
	 * @return 	string
	 */
	public function getHtml(?array $attribs = null, ?string $baseUrl = null) : string
	{
		if (null !== $attribs and count($attribs) > 0) {
			$attribs = array_replace_recursive($this->attribs, $attribs);
		} else {
			$attribs = $this->attribs;
		}
		
		// Image.
		
		$possibles = array('alt', 'title', 'rel', 'width', 'height');
		$imgAttribs = array();
		$figAttribs = array();
		
		foreach ($possibles as $poss) {
			if (isset($attribs[$poss])) {
				$imgAttribs[$poss] = $attribs[$poss];
			}
		}
		
		if (isset($attribs['class'])) {
			if (!isset($attribs['caption'])) {
				$imgAttribs['class'] = $attribs['class'];
			} else {
				$figAttribs['class'] = attrins['class'];
			}
			
			if (!isset($attribs['sizes']) and $this->flags->isFlagNotSet(self::NO_AUTO_SIZES_TAG)) {
				$possibles = array('s25', 's33', 's50', 's66', 's75');
				foreach ($possibles as $pos) {
					if (false !== strpos($attribs['class'], $poss)) {
						$imgAttribs['sizes'] = substr($poss, 1) . 'vw';
					}
				}				
			}
		}
		
		if (!isset($attribs['sizes']) and count($this->subImages) > 0) {
			$imgAttribs['sizes'] = '100vw';
		}

		if (count($this->subImages) > 0) {
			$imgAttribs['srcset'] = '';
			foreach ($this->subImages as $k => $v) {
				if ('' != $imgAttribs['srcset']) {
					$imgAttribs['srcset'] .= ', ';
				}
				$imgAttribs['srcset'] .= $v->getUrl($baseUrl) . ' ' . $k;
			}
		}

		$html = '<img src="' . $this->getUrl($baseUrl) . '"';
								
		foreach ($imgAttribs as $k => $v) {
			$html .= ' ' . $k . '="' . $v . '"';
		}

		$html .= ' />';
		
		
		
		// Link.
		
		if (!isset($attribs['link']) and $this->flags->isFlagNotSet(self::NO_DEFAULT_LINK_SELF)) {
			$attribs['link'] = $this->getUrl($baseUrl);
		} else if (is_array($attribs['link']) and !isset($attribs['link']['href']) and $this->flags->isFlagNotSet(self::NO_DEFAULT_LINK_SELF)) {
			$attribs['link']['href'] = $this->getUrl($baseUrl);
		}
		
		if (is_string($attribs['link'])) {
			$attribs['link'] = array('href' => $attribs['link']);
		}
		
		if ('self' == $attribs['link']['href']) {
			$attribs['link']['href'] = $this->getUrl($baseUrl);
		}
		
		if (isset($attribs['link'])) {
		
			$linkAttribs = $attribs['link'];
			$href = $linkAttribs['href'];
			unset($linkAttribs['href']);
			
			if (isset($linkAttribs['rel'])) {
				$rel = $linkAttribs['rel'];
				unset($linkAttribs['rel']);
			} else {
				$rel = array();
			}
			
			if (isset($linkAttribs['class'])) {
				$linkAttribs['class'] .= ' image';
			} else {
				$linkAttribs['class'] = 'image';
			}
			
			$link = new Link($href, $rel, $linkAttribs);
			
			$html = $link->getHtml($html);
		}
		
		// Figure.
		
		if (isset($attribs['fullCaption'])) {
			$fig = '<figure';
			foreach($figAttribs as $k => $v) {
				$fig .= ' ' . $k . '="' . $v . '"';
			}
			$fig .= '>';
			$html = $fig . $html . '<figcaption>' . $attribs['fullCaption'] . '</figcaption></figure>';
		}

		return $html;

	}	

	/**
	 * Process the imaage.
	 * 
	 * @return 	void
	 *
	 * @throws 	RuntimeException 	If we require a srcset but cannot find one.
	 */
	protected function process()
	{
		$this->processAlt();
		$this->processFullCaption();
		$this->processWidthAndHeight();
		
		if (!$this->isSub) {
			$result = $this->possiblyProcessSrcset();
			if (false === $result and true === $this->srcset) {
				throw new RuntimeException(sprintf("Image %s requires a srcset but we cannot find one", $this->getPathname()));
				return;
			}
		}
	}
	
	/**
	 * Process alt (and title).
	 *
	 * @return void
	 */
	protected function processAlt()
	{
		// Alt and title.
		if (isset($this->attribs['title']) and !isset($this->attribs['alt']) and $this->flags->isFlagNotSet(self::NO_ALT_IS_TITLE)) {
			$this->attribs['alt'] = $this->attribs['title'];
		}
		
		if (isset($this->attribs['alt']) and !isset($this->attribs['title']) and $this->flags->isFlagNotSet(self::NO_TITLE_IS_ALT)) {
			$this->attribs['title'] = $this->attribs['alt'];
		}
		
		if (isset($this->attribs['caption']) and !isset($this->attribs['alt']) and $this->flags->isFlagNotSet(self::NO_ALT_USE_CAPTION)) {
			$this->attribs['alt'] = $this->attribs['caption'];
		}		

		if (isset($this->attribs['caption']) and !isset($this->attribs['title']) and $this->flags->isFlagNotSet(self::NO_TITLE_USE_CAPTION)) {
			$this->attribs['title'] = $this->attribs['caption'];
		}		
	}
	
	/**
	 * Process the full caption.
	 *
	 * @return	void
	 */
	protected function processFullCaption()
	{
		if (isset($this->attribs['caption']) or isset($this->attribs['credit']) or isset($this->attribs['copyr'])) {
			$fullCaption = '';
			if (isset($this->attribs['caption'])) {
				$fullCaption .= $this->attribs['caption'];
			}
			
			$cc = '';
			if (isset($this->attribs['credit'])) {
				if ('' != $cc) {
					$cc .= ', ';
				}
				$cc .= "Credit: " . $this->attribs['credit'];
			}
			
			if (isset($this->attribs['copyr'])) {
				if ('' != $cc) {
					$cc .= ', ';
				}
				$cc .= "Copyright &copy; " . $this->attribs['copyr'];
			}
			
			if ('' != $cc) {
				$cc = '<span class="credit">' . $cc . '</span>';
			
				if ('' != $fullCaption) {
					$fullCaption .= "<br />";
				}
				
				$fullCaption .= $cc;
			}
			
			$this->attribs['fullCaption'] = $fullCaption;
		}
	}		
	
	/**
	 * Process the width and height.
	 *
	 * @return 	void
	 *
	 * @throws 	RuntimeException 	If we cannot extract width and height.
	 */
	protected function processWidthAndHeight()	
	{
		$wh = @getimagesize($this->getPathname());
		if (false !== $wh) {
			$this->attribs['width'] = $wh[0];
			$this->attribs['height'] = $wh[1];
			$this->attribs['mime'] = $wh['mime'];
		} else {
			throw new RuntimeException(sprintf("Unable to extract width and height for %s", $this->getPathname()));
		}
	}
	
	/**
	 * Possibly process the secset.
	 *
	 * @return bool
	 *
	 * @throws 	RuntimeException 	If srcset is an array and we don't have all the sizes therein.
	 */
	protected function possiblyProcessSrcset() : bool
	{
		if (false === $this->srcset) {
			return false;
		}	
		
		$srcsetInfo = $this->extractSrcsetTag($this->getBasename());
		if (false === $srcsetInfo) {
			return false;
		}
		
		$filesGot = array();
		$sizesGot = array();
		
		$di = new DirIter($this->getPath());
		foreach ($di as $fileInfo) {
			if ($fileInfo->isDot() or $fileInfo->isDir()) {
				continue;
			}
			if (preg_match($srcsetInfo['regex'], $fileInfo->getBasename())) {
				$filesGot[] = $fileInfo->getPathname();
				$tag = $this->extractSrcsetTag($fileInfo->getPathname());
				$sizesGot = $tag['sizeNo'];
				$this->addSubImage($tag['sizeStr'], $fileInfo->getPathname(), $this->attribs);
			}
		}
		
		if (is_array($this->srcset)) {
			if (count(array_diff($sizesGot, $this->srcset)) > 0) {
				throw new RuntimeException(sprintf("Not all specified srcset sizes could be found for %s", $this->getPathname()));
				return false;	
			}
		}		
		
		return true;

	}	
	
	/**
	 * Extract a srcset file tag.
	 *
	 * @param 	string 	$file 	File name.
	 *
	 * @return	array|false
	 */
	protected function extractSrcsetTag(string $file)
	{
		$lastDot = strrpos($file, '.');
		if (false === $lastDot) {
			return false;
		}		
		$withoutExt = substr($file, 0, $lastDot);
		
		$lastDash = strrpos($withoutExt, '-');
		if (false === $lastDash) {
			return false;
		}
		$thisSizeTag = substr($withoutExt, $lastDash + 1);
		if ('w' != substr($thisSizeTag, -1)) {
			return false;
		}
		$thisSizeNumber = substr($thisSizeTag, 0, strlen($thisSizeTag) - 1);
		if (!is_numeric($thisSizeNumber)) {
			return false;
		}
		
		$ret = array(
			'pattern'	=>	substr($withoutExt, 0, $lastDash + 1),
			'regex'		=>	'#' . preg_quote(substr($withoutExt, 0, $lastDash + 1)) . '\d+w' . preg_quote(substr($file, $lastDot)) . '#',
			'sizeStr'	=>	$thisSizeTag,
			'sizeNo'	=>	(int)$thisSizeNumber,	
			'file' 		=>	$file,
			'ext'		=>	substr($file, $lastDot),
		);
		
		return $ret;
	}	
	
}
