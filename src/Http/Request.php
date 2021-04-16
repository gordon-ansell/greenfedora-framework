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

use GreenFedora\Http\RequestInterface;
use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;
use GreenFedora\Uri\Uri;
use GreenFedora\Uri\UriInterface;

use GreenFedora\Http\Exception\InvalidArgumentException;

/**
 * HTTP request class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Request implements RequestInterface
{
    /**
     * Variable sets.
     */
    const VAR_SETS = array('get', 'post', 'request', 'env', 'server', 'cookie', 'session', 'files', 'header');

    /**
     * Request URI.
     * @var Uri|null
     */
    protected $requestUri   =  null;

    /**
     * Variables.
     * @var Arr|null
     */
    protected $vars         =   null;

    /**
     * Is dispatched?
     * @var bool
     */
    protected $isDispatched    =   false;
    
    /**
	 * Command line arguments.
	 * @var Arr
	 */
	protected $cmdLineArgs = null;

    /**
	 * Command line switches.
	 * @var Arr
	 */
	protected $cmdLineSwitches = null;

    /**
     * Constructor.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->loadVars();
    }

    /**
     * Load the variables.
     *
     * @return  void
     */
    protected function loadVars()
    {
        $this->vars = new Arr();
        foreach (self::VAR_SETS as $set) {
            $super = '_' . strtoupper($set);
            if ('header' == $set) {
                $this->vars->set($set, apache_request_headers());
            } else {
                if (isset($GLOBALS[$super])) {
                    $this->vars->set($set, $GLOBALS[$super]);
                } else {
                    $this->vars->set($set, array());
                }
            }
        }
        
        $this->loadCmdLineArgs();
    }
    
    /**
	 * Load command line arguments.
	 *
	 * @return 	void
	 */
	protected function loadCmdLineArgs()
	{
		$this->cmdLineArgs = new Arr();
		$this->cmdLineSwitches = new Arr();
		
        if (array_key_exists('argv', $_SERVER) and count($_SERVER['argv'])) {
            foreach ($_SERVER['argv'] as $arg) {
                if (strpos($arg, '=') != false) {
                    $parts = explode("=", $arg);
                    $key = trim($parts[0]);
                    $this->cmdLineArgs->set($key, trim($parts[1]));
                } else if ('--' == substr($arg, 0, 2)) {
                    $switch = substr($arg, 2);
                    $this->cmdLineSwitches->set($switch, true);
                } else if ('-' == substr($arg, 0, 1)) {
                    $switch = substr($arg, 1);
                    $this->cmdLineSwitches->set($switch, true);
                }
            }
        }
	}
	
	/**
	 * Get the command line arguments.
	 *
	 * @return	ArrInterface
	 */
	public function getCommandLineArgs(): ArrInterface
	{
		return $this->cmdLineArgs;
	}

	/**
	 * Get the command line switches.
	 *
	 * @return	ArrInterface
	 */
	public function getCommandLineSwitches(): ArrInterface
	{
		return $this->cmdLineSwitches;
	}

    /**
     * Get the request URI.
     *
     * @return  UriInterface
     */
    public function getRequestUri() : UriInterface
    {
        if (null === $this->requestUri) {
            $scheme = (isset($_SERVER['HTTPS']) and !empty($_SERVER['HTTPS']) and ('on' == $_SERVER['HTTPS']))
                ? 'https://' : 'http://';
            $this->requestUri = new Uri($scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
        return $this->requestUri;
    }

    /**
     * Get the route info.
     *
     * @return  string
     */
    public function getRoute() : string
    {
        return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    }

    /**
     * Get the base URI.
     *
     * @return  string
     */
    public function getBaseUri() : string
    {
	    $ret = $this->getRequestUri()->getUri();
	    if ('/' != $this->getRoute()) {
        	$ret = str_replace($this->getRoute(), '', $ret);
        }
        return rtrim($ret, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the request method.
     *
     * @return  string
     */
    public function getMethod() : string
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * See if this is a POST request.
     *
     * @return  bool
     */
    public function isPost() : bool
    {
        return 'POST' == $this->getMethod();
    }

    /**
     * Get a variable (or whole set).
     *
     * @param   string          $type       Set to get.
     * @param   string|null     $key        Key to get or null for all.
     * @param   mixed           $default    Default to return uf not found.
     * @return  mixed
     * @throws  InvalidArgumentException
     */
    public function getVar(string $type, ?string $key = null, $default = null)
    {
        if (!in_array($type, self::VAR_SETS)) {
            throw new InvalidArgumentException(sprintf("We do not have variables of type '%s'", $type));
        }

        if (null === $this->vars) {
            $this->loadVars();
        }
        
        if (null === $key) {
            return $this->vars->get($type);
        } else {
            return $this->vars->get($type)->get($key, $default);
        }
    }

    /**
     * See if we have a variable (or whole set).
     *
     * @param   string          $type       Set to get.
     * @param   string          $key        Key to get.
     * @return  bool
     * @throws  InvalidArgumentException
     */
    public function hasVar(string $type, string $key) : bool
    {
        if (!in_array($type, self::VAR_SETS)) {
            throw new InvalidArgumentException(sprintf("We do not have variables of type '%s'", $type));
        }

        if (null === $this->vars) {
            $this->loadVars();
        }
        
        return $this->vars->get($type)->has($key);
    }

    /**
     * Set the request as dispatched.
     *
     * @param   bool            $flag       Flag to set.
     * @return  RequestInterface
     */
    public function setDispatched(bool $flag = true) : RequestInterface
    {
        $this->isDispatched = $flag;
        return $this;
    }

    /**
     * Have we been dispatched?
     *
     * @return  bool
     */
    public function isDispatched() : bool
    {
        return $this->isDispatched;
    }

    /**
     * Get get variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function get(?string $key = null, $default = null)
    {
        return $this->getVar('get', $key, $default);
    }

    /**
     * Get post variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function post(?string $key = null, $default = null)
    {
        return $this->getVar('post', $key, $default);
    }

    /**
     * See if we have a post variable.
     *
     * @param   string          $key        Key to get.
     * @return  bool
     */
    public function hasPost(string $key) : bool
    {
        return $this->hasVar('post', $key);
    }

    /**
     * Check the form submitted.
     *
     * @param   string  $form   Form name to check.
     * @return  bool
     */
    public function formSubmitted(string $form): bool
    {
        return ($this->post('form-submitted', null) == $form);
    }

    /**
     * Get request variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function request(?string $key = null, $default = null)
    {
        return $this->getVar('request', $key, $default);
    }

    /**
     * Get env variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function env(?string $key = null, $default = null)
    {
        return $this->getVar('env', $key, $default);
    }

    /**
     * Get server variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function server(?string $key = null, $default = null)
    {
        return $this->getVar('server', $key, $default);
    }

    /**
     * Get cookie variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function cookie(?string $key = null, $default = null)
    {
        return $this->getVar('cookie', $key, $default);
    }

    /**
     * Get session variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function session(?string $key = null, $default = null)
    {
        return $this->getVar('session', $key, $default);
    }

    /**
     * Get files variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function files(?string $key = null, $default = null)
    {
        return $this->getVar('files', $key, $default);
    }

    /**
     * Get header variable(s).
     *
     * @param   string          $key        Key to get.
     * @param   mixed           $default    Default if not found.
     * @return  mixed
     */
    public function header(?string $key = null, $default = null)
    {
        return $this->getVar('header', $key, $default);
    }

}

