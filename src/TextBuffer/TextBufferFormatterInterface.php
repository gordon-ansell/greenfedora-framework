<?php

/**
 * @see       https://github.com/gordon-ansell/greenfedora-ipm for the canonical source repository.
 * @copyright https://github.com/gordon-ansell/greenfedora-ipm/blob/master/COPYRIGHT.md
 * @license   https://github.com/gordon-ansell/greenfedora-ipm/blob/master/LICENSE.md New BSD License.
 */

declare(strict_types=1);
namespace GreenFedora\TextBuffer;

/**
 * Formatter interface.
 */
interface TextBufferFormatterInterface
{
    /**
     * Format a bunch of items.
     * 
     * @param   iterable    $items  Items to format.
     * @return  array
     */
    public function format(iterable $items): array;
}