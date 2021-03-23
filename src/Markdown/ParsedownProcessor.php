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
namespace GreenFedora\Markdown;

use GreenFedora\Markdown\MarkdownInterface;

use Parsedown;

/**
 * Parsedown processor.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ParsedownProcessor implements MarkdownInterface
{
	/**
	 * 3rd party parsedown object.
	 * @var Parsedown
	 */
	protected $pd = null;
	
	/**
	 * Constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		$this->pd = new Parsedown();
	    $this->pd->setMarkupEscaped(true);
	    $this->pd->setUrlsLinked(false);
	}	
	
	/**
	 * Convert to HTML.
	 *
	 * @param	string	$text 		Markdown text to convert.
	 * @param	int 	$decode		HTML decode flags.
	 *
	 * @return	string
	 */
	public function toHtml(string $text, int $decode = ENT_QUOTES) : string
	{
		$ret = $this->pd->text($text);
		if (0 != $decode) {
			$ret = htmlspecialchars_decode($ret, $decode);
		}
		return $ret;
		
	}		
}
