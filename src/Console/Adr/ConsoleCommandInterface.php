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
namespace GreenFedora\Console\Adr;

use GreenFedora\Application\Adr\ActionInterface;
use GreenFedora\TextBuffer\TextBufferInterface;

/**
 * The base interface for console actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ConsoleCommandInterface extends ActionInterface 
{
	/**
	 * Do the auto help.
	 * 
	 * @param 	array 	$classes 	Classes to process.
	 * @return 	array
	 */
	public function autoHelp(array $classes): array;

	/**
	 * Print the auto help.
	 * 
	 * @param 	array 					$classes 	Classes to process.
	 * @param 	TextBufferInterface 	$tb			Buffer to write to.
	 * @param 	int 					$pad 		Padding between key and item.
	 * @return 	TextBufferInterface
	 */
	public function autoHelpPrint(array $classes, TextBufferInterface &$tb, int $pad = 30): TextBufferInterface;

}