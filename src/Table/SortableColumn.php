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
namespace GreenFedora\Table;

use GreenFedora\Table\Column;
use GreenFedora\Table\SortableColumnInterface;
use GreenFedora\Table\TableInterface;
use GreenFedora\Html\Html;

/**
 * Sortable table column.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class SortableColumn extends Column implements SortableColumnInterface
{
    /**
     * Render the header.
     * 
     * @return  string
     */
    public function renderHdr(): string
    {
        return '<button type="submit" class="sortablecolumn-button" name="sort" id="sort" value="' . 
            $this->name .
            '" >' .
            parent::renderHdr() .
            '</button>';
    }

}
