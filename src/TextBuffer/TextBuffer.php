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

use GreenFedora\TextBuffer\TextBufferInterface;
use GreenFedora\TextBuffer\TextBufferOutputFormatterInterface;

/**
 * Simple text buffer class,
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class TextBuffer implements TextBufferInterface
{
    /**
     * The buffer itself.
     * @var array
     */
    protected $buffer = [];

    /**
     * Current line.
     * @var string
     */
    protected $current = '';

    /**
     * End of line string.
     * @var string
     */
    protected $eol = PHP_EOL;

    /**
     * Formatter.
     * @var TextBufferOutputFormatterInterface
     */
    protected $formatter = null;

    /**
     * Constructor.
     * 
     * @param   mixed   $data   Input data.
     * @return  void
     */
    public function __construct($data = null, TextBufferOutputFormatterInterface $formatter = null)
    {
        $this->formatter = $formatter;
        if (!is_null($data)) {
            if (is_iterable($data)) {
                $this->load($data);
            } else {
                $this->write($data);
            }
        }
    }

    /**
     * Load data.
     * 
     * @param   iterable    $data   Data to load.
     * @return  TextBufferInterface
     */
    public function load(iterable $data): TextBufferInterface
    {
        foreach ($data as $line) {
            $this->writeln($data);
        }
        return $this;
    }

    /**
     * Write to the current line.
     * 
     * @param   mixed   $data   Data to write.
     * @return  TextBufferInterface
     */
    public function write($data): TextBufferInterface
    {
        $this->current .= strval($data);
        return $this;        
    }

    /**
     * Prepend to the current line.
     * 
     * @param   mixed   $data   Data to prepend.
     * @return  TextBufferInterface
     */
    public function prepend($data): TextBufferInterface
    {
        $this->current = strval($data) . $this->current;
        return $this;        
    }

    /**
     * Write a line out.
     * 
     * @param   mixed   $data   Data to write.
     * @return  TextBufferInterface
     */
    public function writeln($data): TextBufferInterface
    {
        $this->end();
        $this->current = strval($data);
        $this->end();
        return $this;        
    }

    /**
     * Write ablank line.
     * 
     * @return  TextBufferInterface
     */
    public function blank(): TextBufferInterface
    {
        $this->end();
        $this->end(true);
        return $this;
    }

    /**
     * End the current line.
     * 
     * @param   bool    $evenIfBlank    End it even if blank.
     * @return  TextBufferInterface
     */
    public function end(bool $evenIfBlank = false): TextBufferInterface
    {
        if ('' != $this->current or $evenIfBlank) {
            $this->buffer[] = $this->current;
        } 
        $this->current = '';
        return $this;
    }

    /**
     * Convert the buffer to a string.
     * 
     * @return  string
     */
    protected function bufferToString(): string
    {
        $this->end();
        if (!is_null($this->formatter)) {
            $tmp = $this->formatter->format($this->buffer);
            return implode($this->eol, $tmp);
        } else {
            return implode($this->eol, $this->buffer);
        }
    }

    /**
     * Standard stuff.
     * 
     * @return  string
     */
    public function __toString(): string
    {
        return $this->bufferToString();
    }
}
