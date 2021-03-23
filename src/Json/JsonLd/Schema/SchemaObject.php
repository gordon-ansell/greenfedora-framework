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
use GreenFedora\Json\JsonLd\Schema\SchemaObjectInterface;

/**
 * Schema base object.
 *
 * Currently this is only here to serve as a base for the deeper schema stuff.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SchemaObject extends JsonLd implements SchemaObjectInterface
{
	/**
	 * Context.
	 * @param string
	 */
	protected $context = "http://schema.org";
	
	/**
	 * The actual properties we have.
	 * @var array
	 */
	protected $properties = array();
			 		
}