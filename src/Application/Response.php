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
namespace GreenFedora\Application;

use GreenFedora\Application\ResponseInterface;
use GreenFedora\Application\AbstractMessage;

/**
 * Base output.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Response extends AbstractMessage implements ResponseInterface
{	
    /**
     * Exceptions.
     * @var \Exception[]
     */
    protected $exceptions   =   array();

    /**
     * Constructor.
     * 
	   * @param 	mixed 			    $content 	   Content.
     * @param   iterable        $headers     Headers.    
     * @param   string|null     $protocol    Protocol.
     * @return  void 
     */
    public function __construct($content = '', iterable $headers = array(), ?string $protocol = null)
    {
		    parent::__construct($content, $headers, $protocol);
    }

    /**
     * Add an exception to the response.
     *
     * @param   \Exception    $exception      Exception to add.
     * @return  ResponseInterface
     */
    public function addException(\Exception $exception) : ResponseInterface
    {
        $this->exceptions[] = $exception;
        return $this;
    }

    /**
     * Do we have exceptions?
     *
     * @return bool
     */
    public function hasExceptions() : bool
    {
        return (count($this->exceptions) > 0);
    }
}
