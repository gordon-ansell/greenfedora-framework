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
namespace GreenFedora\Adr\Responder;


/**
 * The base interface for all responders.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ResponderInterface
{
    /**
     * Dispatcher.
     *
     * @return void
     */
	public function dispatch();	
}