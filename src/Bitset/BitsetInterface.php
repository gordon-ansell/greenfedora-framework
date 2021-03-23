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
namespace GreenFedora\Bitset;

/**
 * Bitset interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface BitsetInterface
{
    /**
     * Clone this object with possible additional flags.
     *
     * @param   int|null    $flags      Overriding flags.
     *
     * @return  BitsetInterface
     */
    public function withFlags(?int $flags = null) : BitsetInterface;
    
    /**
	 * Get the flags.
	 *
	 * @return 	int
	 */
	public function getFlags() : int;

    /**
     * See if a flag is set.
     *
     * @param   int     $flag       Flag to test.
     *
     * @return  bool
     */
    public function isFlagSet(int $flag) : bool;

    /**
     * See if a flag is NOT set.
     *
     * @param   int     $flag       Flag to test.
     *
     * @return  bool
     */
    public function isFlagNotSet(int $flag) : bool;
    
    /**
     * Set a flag.
     *
     * @param   int     $flag       Flag to set.
     *
     * @return  BitsetInterface
     */
    public function setFlag(int $flag) : BitsetInterface;
    
    /**
     * Unset a flag.
     *
     * @param   int     $flag       Flag to unset.
     *
     * @return  BitsetInterface
     */
    public function unsetFlag(int $flag) : BitsetInterface;
}