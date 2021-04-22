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
namespace GreenFedora\Http;

use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\Http\Exception\HeadersSentException;

/**
 * HTTP response interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface HttpResponseInterface extends ApplicationOutputInterface
{
    /**
     * Set the status code.
     *
     * @param   int         $code       Code to set.
     * @return  self
     */
    public function setStatusCode(int $code) : self;

    /**
     * Set the console code.
     *
     * @param   int         $code       Code to set.
     * @return  self
     */
    public function setConsoleCode(int $code) : self;

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode() : int;

    /**
     * Get the console code.
     *
     * @return int
     */
    public function getConsoleCode() : int;

    /**
     * Set a header.
     *
     * @param   string      $name       Header name.
     * @param   mixed       $value      Header value.
     * @param   bool        $replace    Replace existing header?
     * @return  self
     */
    public function setHeader(string $name, $value, bool $replace = false) : self;

    /**
     * See if we can send headers.
     *
     * @param   bool    $throw      Throw exception if sent?
     * @return  bool
     * @throws  HeadersSentException
     */
    public function canSendHeaders(bool $throw = false) : bool;

    /**
     * Send the headers.
     *
     * @return  self
     */
    public function sendHeaders() : self;

    /**
     * Set the body.
     *
     * @param   mixed       $body       Body content.
     * @return  self
     */
    public function setBody($body) : self;

    /**
     * Send the body.
     *
     * @return  self
     */
    public function sendBody() : self;

    /**
     * Send the request.
     *
     * @return void
     */
    public function send();

    /**
     * Add an exception to the response.
     *
     * @param   \Exception    $exception      Exception to add.
     * @return  self
     */
    public function addException(\Exception $exception) : self;

    /**
     * Do we have exceptions?
     *
     * @return bool
     */
    public function hasExceptions() : bool;


}

