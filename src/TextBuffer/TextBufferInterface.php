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
namespace GreenFedora\TextBuffer;

/**
 * Simple text buffer interface,
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
interface TextBufferInterface
{
    /**
     * Load data.
     * 
     * @param   iterable    $data   Data to load.
     * @return  TextBufferInterface
     */
    public function load(iterable $data): TextBufferInterface;

    /**
     * Write to the current line.
     * 
     * @param   mixed   $data   Data to write.
     * @return  TextBufferInterface
     */
    public function write($data): TextBufferInterface;

    /**
     * Prepend to the current line.
     * 
     * @param   mixed   $data   Data to prepend.
     * @return  TextBufferInterface
     */
    public function prepend($data): TextBufferInterface;

    /**
     * Write a line out.
     * 
     * @param   mixed   $data   Data to write.
     * @return  TextBufferInterface
     */
    public function writeln($data): TextBufferInterface;

    /**
     * Write ablank line.
     * 
     * @return  TextBufferInterface
     */
    public function blank(): TextBufferInterface;

    /**
     * End the current line.
     * 
     * @param   bool    $evenIfBlank    End it even if blank.
     * @return  TextBufferInterface
     */
    public function end(bool $evenIfBlank = false): TextBufferInterface;

    /**
     * Standard stuff.
     * 
     * @return  string
     */
    public function __toString(): string;
}
