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

use GreenFedora\FileSystem\DirIterFilter\FilterInterface;
use GreenFedora\FileSystem\DirIterFilter\Exception\InvalidArgumentException;
use GreenFedora\FileSystem\FileInfoInterface;
use GreenFedora\FileSystem\DirIterInterface;
use GreenFedora\Bitset\Bitset;
use GreenFedora\Arr\Arr;
use GreenFedora\Logger\LogMessageSaverTrait;
use GreenFedora\Logger\LogMessageSaverInterface;

/**
 * Filter for directory iterators.
 */	
class Filter implements FilterInterface, LogMessageSaverInterface
{	 	
	use LogMessageSaverTrait;
	
	/**
	 * Fiesystem Defs.
	 * @var Arr
	 */
	protected $defs = null; 
	
	/**
	 * Constructor.
	 *
	 * @param 	iterable	$defs	Filtering definitions.
	 *
	 * @return	void
	 */
	public function __construct(iterable $defs)
	{
		if (is_array($defs)) {
			$this->defs = new Arr($defs);
		} else {
			$this->defs = $defs;
		}
		$this->validateRules();
	}
	
	/**
	 * Validate the rules.
	 *
	 * @return 	void
	 */
	protected function validateRules()
	{
		foreach ($this->defs->rules as $id => $rule) {
			
			if (!$rule->has('scope') or 0 == $rule->scope) {
				throw new InvalidArgumentException(sprintf("Directory iterator filter rule '%s' needs a 'scope'", $id));	
			} else if (!$rule->has('spec')) {
				throw new InvalidArgumentException(sprintf("Directory iterator filter rule '%s' needs a 'spec'", $id));	
			}
			
		}
	} 	
	
	/**
	 * Check something against the rules.
	 *
	 * @param	DirIterInterface	$fileInfo	The file info interface.
	 * @param 	string 				$rr 		Root relative path.
	 *
	 * @return	bool	True if we ignore it, else false.
	 */
	public function ignore(DirIterInterface $fileInfo, string $rr) : bool
	{
		foreach ($this->defs->rules as $id => $rule) {
			
			if (FilterInterface::TYPE_BEGINSWITH == $rule->type) {
				
				$scope = new Bitset($rule->scope);	
				$spec = $rule->spec;
				
				if ($rule->has('not')) {
					$not = $rule->not;
					if ($not->has(1) and 'var' == $not->get(1)) {
						$var = $not->get(0);
						if ($this->defs->get($var)->in($rr)) {
							continue;
						}
					}
				}
				
				if ($scope->isFlagSet(FilterInterface::SCOPE_FILE) and $fileInfo->isFile() and substr($fileInfo->getBasename(), 0, strlen($spec)) == $spec) {
					$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '1', 'file scope'));
					return true;
				} else if ($scope->isFlagSet(FilterInterface::SCOPE_DIR) and $fileInfo->isDir() and substr($fileInfo->getBasename(), 0, strlen($spec)) == $spec) {
					$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '2', 'directory scope'));
					return true;
				} else if ($scope->isFlagSet(FilterInterface::SCOPE_PATH) and substr($rr, 0, strlen($spec)) == $spec) {
					$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '3', 'path scope'));
					return true;
				}
				
			} else if (FilterInterface::TYPE_PATH == $rule->type) {
				
				$check = $this->defs->{$rule->spec};

				if ($scope->isFlagSet(FilterInterface::SCOPE_FILE) and $fileInfo->isFile() and $check->in($fileInfo->getBasename())) {
					$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '1', 'file scope'));
					return true;
				} else if ($scope->isFlagSet(FilterInterface::SCOPE_DIR) and $fileInfo->isDir() and $check->in($fileInfo->getBasename())) {
					$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '2', 'directory scope'));
					return true;
				} else if ($scope->isFlagSet(FilterInterface::SCOPE_PATH)) {
					foreach ($check as $single) {
						if (substr($rr, 0, strlen($single)) == $single) {
							$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '3', 'path scope'));
							return true;
						}
					}
				}
				
			} else if (FilterInterface::TYPE_EXT == $rule->type) {
				
				$check = $this->defs->{$rule->spec};
				
				if ($fileInfo->isFile() and $check->in($fileInfo->getExtension())) {
					$this->lsave('trace3', sprintf("%s ignored by rule %s.%s %s.", $rr, $id, '1', 'file scope'));
					return true;
				}
				
			}
			
		}
		
		return false;
	}	
	
}
