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
namespace GreenFedora\FileSystem\Media\Exception;

use GreenFedora\FileSystem\Media\Exception\ExceptionInterface;


/**
 * Runtime problem.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
