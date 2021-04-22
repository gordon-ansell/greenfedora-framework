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
namespace GreenFedora\Console;

use GreenFedora\Console\Exception\OutOfBoundsException;
use GreenFedora\Application\RequestInterface;


/**
 * Command line options.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ConsoleRequestInterface extends RequestInterface
{	
}