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

use GreenFedora\Bitset\BitsetInterface;

/**
 * Bitset.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Bitset implements BitsetInterface
{
    /**
     * Flag container.
     * @var int
     */
    protected $flags = 0;

    /**
     * Constructor.
     *
     * @param   int     $flags      Initial flags.
     *
     * @return  void
     */
    public function __construct(int $flags = 0)
    {
        $this->flags = $flags;
    }

    /**
     * Clone this object with possible additional flags.
     *
     * @param   int|null    $flags      Overriding flags.
     *
     * @return  BitsetInterface
     */
    public function withFlags(?int $flags = null) : BitsetInterface
    {
        $new = clone $this;
        if (null !== $flags) {
            $new->setFlag($flags);
        }
        return $new;
    }
    
    /**
	 * Get the flags.
	 *
	 * @return 	int
	 */
	public function getFlags() : int
	{
		return $this->flags;	
	}

    /**
     * See if a flag is set.
     *
     * @param   int     $flag       Flag to test.
     *
     * @return  bool
     */
    public function isFlagSet(int $flag) : bool
    {
        return (($this->flags & $flag) == $flag);
    }

    /**
     * See if a flag is NOT set.
     *
     * @param   int     $flag       Flag to test.
     *
     * @return  bool
     */
    public function isFlagNotSet(int $flag) : bool
    {
        return !$this->isFlagSet($flag);
    }

    /**
     * Set a flag.
     *
     * @param   int     $flag       Flag to set.
     *
     * @return  BitsetInterface
     */
    public function setFlag(int $flag) : BitsetInterface
    {
        $this->flags |= $flag;
        return $this;
    }

    /**
     * Unset a flag.
     *
     * @param   int     $flag       Flag to unset.
     *
     * @return  BitsetInterface
     */
    public function unsetFlag(int $flag) : BitsetInterface
    {
        $this->flags &= ~$flag;
        return $this;
    }
	
}