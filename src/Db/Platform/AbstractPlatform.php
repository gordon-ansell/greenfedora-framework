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
namespace GreenFedora\Db\Platform;

use GreenFedora\Arr\Arr;

/**
 * Database platform.
 */
abstract class AbstractPlatform
{
    /**
     * Configs.
     * @var Arr
     */
    protected $config = null;

    /**
     * ID quote character.
     * @var string
     */
    protected $idqChar = '';

    /**
     * Column types.
     * @var array
     */
    protected $colTypes = array();

    /**
     * Constructor.
     *
     * @param   Arr         $config     Configs.
     * @return  void
     */
    public function __construct(Arr $config)
    {
        $this->config = $config;
    }

    /**
     * Quote an ID.
     *
     * @param   string      $raw        Raw string.
     * @return  string
     */
    public function idq(string $raw) : string
    {
        if (substr($raw, 0, 1) == $this->idqChar) {
            return $raw;
        }

        if (false === strpos($raw, '.')) {
            return $this->idqChar . $raw . $this->idqChar;
        } else {
            $split = explode('.', $raw);
            $split[0] = $this->idqChar . $this->tn($split[0], false) . $this->idqChar;
            if ('*' != $split[1]) {
                $split[1] = $this->idqChar . $split[1] . $this->idqChar;
            }
            return implode('.', $split);
        }
    }

    /**
     * Get a prefixed table name.
     *
     * @param   string      $raw        Raw table name.
     * @param   bool        $quote      Quote it?
     * @return  string
     */
    public function tn(string $raw, bool $quote = true) : string
    {
        $pref = $this->config->get('pref', '');
        if ($quote and (substr($raw, 0, 1) != $this->idqChar)) {
            return $this->idqChar . $pref . $raw . $this->idqChar;
        } else {
            return $pref . $raw;
        }
    }

    /**
     * Get the column types.
     *
     * @return  array
     */
    public function getColTypes() : array
    {
        return $this->colTypes;
    }

    /**
     * Get a particular column type.
     *
     * @param   string      $type       Type to get.
     * @return  array|null
     */
    public function getColType(string $type) : ?array
    {
        if (array_key_exists(strtoupper($type), $this->colTypes)) {
            return $this->colTypes[strtoupper($type)];
        }
        return null;
    }
}
