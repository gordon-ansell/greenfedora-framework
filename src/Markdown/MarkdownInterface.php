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

/**
 * Markdown processor interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface MarkdownInterface
{
	/**
	 * Convert to HTML.
	 *
	 * @param	string	$text 		Markdown text to convert.
	 * @param	int 	$decode		HTML decode flags.
	 *
	 * @return	string
	 */
	public function toHtml(string $text, int $decode = ENT_QUOTES) : string;
}
