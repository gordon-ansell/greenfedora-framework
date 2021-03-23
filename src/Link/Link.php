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
namespace GreenFedora\Link;

use GreenFedora\Link\LinkInterface;

/**
 * Psr-13 complaint link.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Link implements LinkInterface
{
    /**
     * Href
     * @var string
     */
    protected $href = '';
    
    /**
	 * Rels.
	 * @var array
	 */
	protected $rels = array();
	
	/**
	 * Attribites.
	 * @var array
	 */
	protected $attributes = array();	   

    /**
     * Constructor.
     *
     * @param   string		$href			Href (URL).
     * @param 	array 		$rels			Rels.
     * @param 	array		$attributes		Link attributes.
     *
     * @return  void
     */
    public function __construct(string $href, array $rels = array(), array $attributes = array())
    {
        $this->href = $href;
        $this->rels = $rels;
        $this->attributes = $attributes;
    }
    
    /**
	 * Return this link as HTML.
	 *
	 * @param 	string 		$content 	Content to include.
	 * @param 	bool 		$head 		Is this a head <link>?
	 *
	 * @return 	string
	 */
	public function getHtml(string $content = '', bool $head = false) : string
	{
		$ret = $head ? '<link ' : '<a ';
		$ret .= 'href="' . $this->href . '"';
		
		if (count($this->rels)) {
			$ret .= ' rel="' . implode(' ', array_keys($this->rels)) . '"';	
		}
		
		if (count($this->attributes)) {
			foreach ($this->attributes as $k => $v) {
				$ret .= ' ' . $k . '="' . $v . '"';
			}
		}
		
		if ($head) {
			$ret .= '/>';	
		} else {
			$ret .= '>' . $content . '</a>';
		}
		return $ret;
	}   

    /**
     * Returns the target of the link.
     *
     * The target link must be one of:
     * - An absolute URI, as defined by RFC 5988.
     * - A relative URI, as defined by RFC 5988. The base of the relative link
     *     is assumed to be known based on context by the client.
     * - A URI template as defined by RFC 6570.
     *
     * If a URI template is returned, isTemplated() MUST return True.
     *
     * @return string
     */
    public function getHref()
    {
	    return $this->href;
    }

    /**
	 * Set the href.
	 *
	 * @param 	string		$href 		Href to set.
	 *
	 * @return 	LinkInterface
	 */
	public function setHref(string $href) : LinkInterface
	{
		$this->href = href;
		return $this;
	}   

    /**
     * Returns whether or not this is a templated link.
     *
     * @return bool		True if this link object is templated, False otherwise.
     */
    public function isTemplated()
    {
	    return false;
    }

    /**
     * Returns the relationship type(s) of the link.
     *
     * This method returns 0 or more relationship types for a link, expressed
     * as an array of strings.
     *
     * @return string[]
     */
    public function getRels()
    {
	    return $this->rels;
    }
    
    /**
	 * Add a rel.
	 *
	 * @param 	string		$rel 		Rel to add.
	 *
	 * @return 	LinkInterface
	 */
	public function addRel(string $rel) : LinkInterface
	{
		$this->rels[$rel] = true;
		return $this;
	}   

    /**
	 * Remove a rel.
	 *
	 * @param 	string		$rel 		Rel to remove.
	 *
	 * @return 	LinkInterface
	 */
	public function removeRel(string $rel) : LinkInterface
	{
		if (array_key_exists($rel, $this->rels)) {
			unset($this->rels[$rel]);
		}
		return $this;
	}   

    /**
     * Returns a list of attributes that describe the target URI.
     *
     * @return array
     *   A key-value list of attributes, where the key is a string and the value
     *  is either a PHP primitive or an array of PHP strings. If no values are
     *  found an empty array MUST be returned.
     */
    public function getAttributes()
    {
	    return $this->attributes;
    }
    
    /**
	 * Add an array of attribites.
	 *
	 * @param 	array		$attribs 		Attributes.
	 *
	 * @return 	LinkInterface
	 */
	public function setAttributes(array $attribs) : LinkInterface
	{
		foreach ($attribs as $k => $v) {
			$this->setAttribute($k, $v);
		}
		return $this;
	}   

    /**
	 * Add an attribite.
	 *
	 * @param 	string		$key 		Attribute key.
	 * @param 	mixed 		$val 		Attribute value.
	 *
	 * @return 	LinkInterface
	 */
	public function setAttribute(string $key, $val) : LinkInterface
	{
		$this->attributes[$key] = (string)$val;
		return $this;
	}   

    /**
	 * Remove an attribite.
	 *
	 * @param 	string		$key 		Attribute key.
	 *
	 * @return 	LinkInterface
	 */
	public function removeAttribute(string $key) : LinkInterface
	{
		if (array_key_exists($key, $this->attributes)) {
			unset($this->attributes[$key]);
		}
		return $this;
	}   

    /**
     * Returns an instance with the specified href.
     *
     * @param string $href
     *   The href value to include.  It must be one of:
     *     - An absolute URI, as defined by RFC 5988.
     *     - A relative URI, as defined by RFC 5988. The base of the relative link
     *       is assumed to be known based on context by the client.
     *     - A URI template as defined by RFC 6570.
     *     - An object implementing __toString() that produces one of the above
     *       values.
     *
     * An implementing library SHOULD evaluate a passed object to a string
     * immediately rather than waiting for it to be returned later.
     *
     * @return LinkInterface
     */
    public function withHref($href)
    {
	    $ret = clone $this;
	    $ret->setHref($href);
	    return $ret;
    }

    /**
     * Returns an instance with the specified relationship included.
     *
     * If the specified rel is already present, this method MUST return
     * normally without errors, but without adding the rel a second time.
     *
     * @param string $rel
     *   The relationship value to add.
     *
     * @return LinkInterface
     */
    public function withRel($rel)
    {
	    $ret = clone $this;
	    $ret->addRel($rel);
	    return $ret;
    }

    /**
     * Returns an instance with the specified relationship excluded.
     *
     * If the specified rel is already not present, this method MUST return
     * normally without errors.
     *
     * @param string $rel
     *   The relationship value to exclude.
     *
     * @return LinkInterface
     */
    public function withoutRel($rel)
    {
	    $ret = clone $this;
	    $ret->removeRel($rel);
	    return $ret;
    }

    /**
     * Returns an instance with the specified attribute added.
     *
     * If the specified attribute is already present, it will be overwritten
     * with the new value.
     *
     * @param string $attribute
     *   The attribute to include.
     * @param string $value
     *   The value of the attribute to set.
     *
     * @return LinkInterface
     */
    public function withAttribute($attribute, $value)
    {
	    $ret = clone $this;
	    $ret->setAttribute($attribute, $value);
	    return $ret;
    }


    /**
     * Returns an instance with the specified attribute excluded.
     *
     * If the specified attribute is not present, this method MUST return
     * normally without errors.
     *
     * @param string $attribute
     *   The attribute to remove.
     *
     * @return LinkInterface
     */
    public function withoutAttribute($attribute)
    {
	    $ret = clone $this;
	    $ret->removeAttribute($attribute);
	    return $ret;
    }
	
}
