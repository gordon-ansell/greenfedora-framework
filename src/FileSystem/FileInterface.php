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
namespace GreenFedora\FileSystem;

use GreenFedora\FileSystem\Exception\RuntimeException;
use GreenFedora\FileSystem\FileCommonInterface;

/**
 * Interface for object.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface FileInterface extends FileCommonInterface
{

    /**
     * Return included contents of a file.
     *
     * @return  mixed
     * @throws  RuntimeException	If the file is not readable.
     * @throws  RuntimeException	If the file isn't a file at all.
     */
    public function getIncludedContents();
}
