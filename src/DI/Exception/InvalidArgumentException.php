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
namespace GreenFedora\DI\Exception;

use GreenFedora\DI\Exception\ExceptionInterface;

use Psr\Container\NotFoundExceptionInterface;


/**
 * Invalid argument passed to DependencyInjection.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class InvalidArgumentException extends \InvalidArgumentException implements NotFoundExceptionInterface, ExceptionInterface
{
}
