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
namespace GreenFedora\Logger;

use Psr\Log\Loglevel as PsrLogLevel;

/**
 * Log levels.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class LogLevel extends PsrLogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
    const TRACE     = 'trace';
    const TRACE2   	= 'trace2';
    const TRACE3   	= 'trace3';
    const TRACE4   	= 'trace4';
}