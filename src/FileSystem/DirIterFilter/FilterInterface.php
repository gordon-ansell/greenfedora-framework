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
namespace GreenFedora\FileSystem\DirIterFilter;

use GreenFedora\FileSystem\FileInfoInterface;
use GreenFedora\FileSystem\DirIterInterface;

/**
 * Filter interface for directory iterators.
 */	
interface FilterInterface
{
	/**
	 * Allowable types.
	 * @var int
	 */
	const TYPE_BEGINSWITH 	= 	1;
	const TYPE_PATH			=	2; 
	const TYPE_EXT			=	3;
	 	
	/**
	 * Allowable scopes.
	 * @var int
	 */
	const SCOPE_FILE		=	1;
	const SCOPE_DIR 		=	2;
	const SCOPE_PATH		=	4;	
	const SCOPE_ANY			=	7;

	/**
	 * Check something against the rules.
	 *
	 * @param	DirIterInterface	$fileInfo	The file info interface.
	 * @param 	string 				$rr 		Root relative path.
	 *
	 * @return	bool	True if we ignore it, else false.
	 */
	public function ignore(DirIterInterface $fileInfo, string $rr) : bool;	 		
}
