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
use GreenFedora\Html\Html;

/**
 * Psr-13 complaint link.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Link extends Html implements LinkInterface
{
    /**
     * Constructor.
     *
     * @param   array   $params     Parameters.
     * @return  void
     */
    public function __construct(array $params = [])
    {
        parent::__construct('a', $params);
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
        if ($head) {
            $this->setTag('link');
        }
        return $this->render($content);
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
        if ($this->hasParam('href')) {
	        return $this->getParam('href');
        } else {
            return null;
        }
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
        $this->setParam('href', $href);
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
        if ($this->hasParam('rel')) {
	        return implode(' ', $this->getParam('rel'));
        } else {
            return [];
        }
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
        $this->appendParam('rel', $rel);
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
        if (!$this->hasParam('rel')) {
            return $this;
        }

        $rels = explode(' ', $this->getParam('rel'));
        unset($rels[$rel]);
        $this->setParam('rel', implode(' ', $rels));

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
	    return $this->getParams();
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
        $this->setParams($attribs);
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
        $this->setParam($key, $val);
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
        $this->removeParam($key);
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
	    $ret->setParam('href', $href);
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
	    $ret->appendParam('rel', $rel);
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
	    $ret->removeParamItem('rel', $rel);
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
