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
namespace GreenFedora\Form;

use GreenFedora\Session\SessionInterface;
use GreenFedora\Http\HttpRequestInterface;
use GreenFedora\Arr\ArrInterface;
use GreenFedora\Form\FormPersistHandlerInterface;

use GreenFedora\Logger\InternalDebugTrait;

/**
 * Form persistance handler.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class FormPersistHandler implements FormPersistHandlerInterface
{
    use InternalDebugTrait;

    /**
     * Session.
     * @var SessionInterface
     */
    protected $session = null;

    /**
     * Request.
     * @var HttpRequestInterface
     */
    protected $request = null;

    /**
     * Cookie names and defaults.
     * @var array
     */
    protected $names = [];

    /**
     * Cookie prefix.
     * @var string
     */
    protected $prefix = null;

    /**
     * Constructor.
     * 
     * @param   SessionInterface        $session    Session handler.
     * @param   HttpRequestInterface    $request    Request.
     * @param   array                   $names      Cookie names and defaults.
     * @param   string                  $prefix     Cookie prefix.
     * @return  void
     */
    public function __construct(SessionInterface $session, HttpRequestInterface $request, array $names, string $prefix)
    {
        $this->session = $session;
        $this->request = $request;
        $this->names = $names;
        $this->prefix = $prefix;
    }

    /**
     * Get the session handler.
     * 
     * @return  SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * Load the cookies.
     * 
     * @param   ArrInterface    $target     Where to load them.
     * @return  void
     */
    public function load(ArrInterface &$target)
    {
        foreach ($this->names as $key => $default) {
            $this->debug(sprintf("Loading form persist for: %s = %s", $key, $this->session->get($this->prefix . $key, $default)));
            $target->set($key, $this->session->get($this->prefix . $key, $default));
        }
    }

    /**
     * Save the cookies.
     * 
     * @param   ArrInterface    $source     Where to get the data.
     * @return  void
     */
    public function save(ArrInterface $source)
    {
        foreach ($this->names as $key => $default) {
            if ($source->has($key)) {
                $this->debug(sprintf("Saving form persist for: %s = %s (real data)", $key, strval($source->get($key))));
                $this->session->set($this->prefix . $key, strval($source->get($key)));
            } else {
                $this->debug(sprintf("Saving form persist for: %s = %s (default data)", $key, strval($default)));
                $this->session->set($this->prefix . $key, strval($default));
            }
        }
    }
}