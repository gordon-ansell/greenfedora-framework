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
namespace GreenFedora\Facade;

use GreenFedora\Facade\AbstractFacade;

/**
 * Logger facade.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 * 
 * 
 * @method static level(?string $level = null) : string;
 * @method static getMessageCount(string $level) : int;
 * @method static getMessageCounts() : array;
 * @method static log($level, $message, array $context = array());
 * @method static emergency($message, array $context = array());
 * @method static alert($message, array $context = array());
 * @method static critical($message, array $context = array());
 * @method static error($message, array $context = array());
 * @method static warning($message, array $context = array());
 * @method static notice($message, array $context = array());
 * @method static info($message, array $context = array());
 * @method static debug($message, array $context = array());
 * @method static trace($message, array $context = array());
 * @method static trace2($message, array $context = array());
 * @method static trace3($message, array $context = array());
 * @method static trace4($message, array $context = array());
 * 
 * Call as Logger::method(params) or (new Logger)->method(params) to avoid strict warnings.
 * Facades should only be used as a temporray measure, for example when debugging something.
 */

class Logger extends AbstractFacade
{
    /**
     * Get the facade key.
     * 
     * @return  string
     */
    protected static function facadeKey(): string
    {
        return 'logger';
    }

}