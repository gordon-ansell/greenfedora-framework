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
namespace GreenFedora\Json\JsonLd\Schema;

use GreenFedora\Json\JsonLd\JsonLd;
use GreenFedora\Json\JsonLd\Schema\SchemaConverterInterface;
use GreenFedora\Json\JsonLd\Schema\SchemaObjectInterface;
use GreenFedora\Json\JsonLd\Schema\SchemaObject;

/**
 * Takes an array of key/values and coverts them to scheme via a set of rules.
 *
 * Currently this is only here to serve as a base for the deeper schema stuff.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SchemaConverter implements SchemaConverterInterface
{
	/**
	 * Input values.
	 * @var iterator
	 */
	protected $input = array();
	
	/**
	 * Rules.
	 * @var iterator
	 */
	protected $rules = array(
		'id'		=>	'',
		'type'		=>	'',
		'at-root' 	=> 	array(),
		'must-have'	=>	array(),
		'can-have'	=>	array(),
	);	
	
	/**
	 * Converted schema.
	 * @var SchemaObjectInterface
	 */
	protected $object = null;	
	
	/**
	 * Constructor.
	 *
	 * @param 	iterator		$input		Input values.
	 * @param 	iterator|null 	$rules 		Rules to use when converting.
	 *
	 * @return	void
	 */
	public function __construct(iterator $input, ?iterator $rules = null)
	{
		$this->input = $input;
		if (!is_null($rules)) {
			$this->rules = $rules;
		}
	}
	
	/**
	 * Convert stuff.
	 *
	 * @return	SchemaObjectInterface
	 */
	public function convert() : SchemaObjectInterface
	{
		
	}			
}
