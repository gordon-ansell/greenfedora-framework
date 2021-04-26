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

use GreenFedora\DI\ContainerAwareTrait;
use GreenFedora\DI\ContainerAwareInterface;
use GreenFedora\DI\ContainerInterface;

/**
 * The base for console actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ConsoleCommandSetInterface
{
    /**
     * Add a command.
     * 
     * @param   string  $name   Command name.
     * @param   string  $class  Command class.
     * @return  ConsoleCommandSetInterface 
     */
    public function addCommand(string $name, string $class): ConsoleCommandSetInterface;
}