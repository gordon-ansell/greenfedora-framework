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
namespace GreenFedora\Html;

use GreenFedora\Html\HtmlInterface;

/**
 * HTML statement base class.
 */
class Html implements HtmlInterface
{
	/**
	 * Tag.
	 * @var string
	 */
	protected $tag = '';

    /**
     * Parameters.
     * @var array
     */
    protected $params = [];

    /**
     * Data.
     * @var string|null
     */
    protected $data = null;
	
	/**
	 * Self closing?
	 * @var bool|null
	 */
	protected $selfClose = null;	

	/**
	 * Self closing tags.
	 * @var array
	 */
	protected $selfClosers = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link',
		'meta', 'param', 'source', 'track', 'wbr'];

    /**
     * Constructor.
     * 
     * @param   string      $tag        HTML tag.
     * @param   array       $params     Parameters.
     * @param   string|null $data       Data.
     * @param   bool|null   $selfClose  Self-closing?
     */
    public function __construct(string $tag, array $params = [], ?string $data = null, ?bool $selfClose = null)
    {
        $this->tag = $tag;
        $this->setParams($params);
        $this->data = $data;
		$this->setSelfClose($selfClose);
    }
		 	
	/**
	 * Build the statement.
	 *
	 * $param 	string|null	$data 	Data.
	 * @return 	string
	 */
	public function build(?string $data = null) : string
	{
		$ret = '<' . $this->tag;
		
		$params = '';
		foreach ($this->params as $key => $value) {
			$standalone = is_bool($value);
			if (!empty($value) or $standalone) {
				if ('' != $params) {
					$params .= ' ';
				}
				if ($standalone) {
					$params .= $key;
				} else {
					$params .= $key . '="' . $value . '"';
				}
			} 
		}
		
		if ('' != $params) {
			$params = ' ' . $params;
		}
		
		$ret .= $params;

		if ('input' == $this->tag) {
			echo $this->selfClose;
		}
		
		if ($this->selfClose) {
			$ret .= ' />';
		} else {
			$ret .= '>';
			if ((null === $data) and (null !== $this->data)) {
				$data = $this->data;
			}
			if (null !== $data) {
				$ret .= $data;
			}
			$ret .= $this->beforeClosingTag();
			$ret .= '</' . $this->tag . '>';
		}
		
		return $ret;
	}
	
	/**
	 * Set the data.
	 *
	 * @param 	mixed 	$data 	Data to set.
	 * @return  HtmlInterface
	 */
	public function setData($data) : HtmlInterface
	{
		$this->data = $data;
		return $this;
	}

    /**
     * Set a parameter.
     * 
     * @param   string  $name   Name of parameter to set.
     * @param   mixed   $val    Value to set.
     * @return  HtmlInterface 
     */
    public function setParam(string $name, $val): HtmlInterface
    {
        $this->params[$name] = $val;
        return $this;
    }
	
    /**
     * Set a parameter array.
     * 
     * @param   array   $params   Array of parameters.
     * @return  HtmlInterface 
     */
    public function setParams(array $params): HtmlInterface
    {
		foreach ($params as $key => $value) {
			$this->setParam($key, $value);
		}
        return $this;
    }

	/**
	 * Set the self-closing flag.
	 *
	 * @param 	bool 	$sc 	Value.
	 * @return  HtmlInterface
	 */
	public function setSelfClose(?bool $sc = true) : HtmlInterface
	{
		if (null === $sc) {
			$this->selfClose = in_array($this->tag, $this->selfClosers);
		} else {
			$this->selfClose = $sc;
		}
		return $this;
	}

    /**
	 * Build anything before the closing tag.
	 *
	 * @return string
	 */
	protected function beforeClosingTag() : string
	{
		return '';
	}	
}
