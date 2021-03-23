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
namespace GreenFedora\FileSystem\Yaml;

use GreenFedora\FileSystem\Yaml\YamlFileInterface;

/**
 * YAML data object interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface FileHasYamlInterface extends YamlFileInterface
{
	/**
	 * Get the content.
	 *
	 * @return 	string
	 */
	public function getContent() : string;
}
