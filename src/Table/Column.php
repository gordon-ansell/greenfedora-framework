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

use GreenFedora\Table\ColumnInterface;
use GreenFedora\Table\TableInterface;
use GreenFedora\Html\Html;

/**
 * Table column.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Column implements ColumnInterface
{
    /**
     * Table.
     * @var TableInterface
     */
    protected $table;

    /**
     * Title.
     * @var string
     */
    protected $title = '';

    /**
     * Header parameters.
     * @var array
     */
    protected $hdrParams = [];

    /**
     * Body parameters.
     * @var array
     */
    protected $bodyParams = [];

    /**
     * Column header class.
     * @var string
     */
    protected $hdrClass = null;

    /**
     * Column body class.
     * @var string
     */
    protected $bodyClass = null;

    /**
     * Header tag.
     * @var string
     */
    protected $hdrTag = 'th';

    /**
     * Body tag.
     * @var string
     */
    protected $bodyTag = 'td';

    /**
     * Constructor.
     * 
     * @param   TableInterface  $table          Parent table.
     * @param   string          $title          Column title.
     * @param   string|null     $hdrClass       Column header class.
     * @param   string|null     $bodyClass      Column body class.
     * @param   array           $hdrParams      Header parameters.
     * @param   array           $bodyParams     Body parameters.
     * @return  void
     */
    public function __construct(TableInterface $table, string $title = '', ?string $hdrClass = null, 
        ?string $bodyClass = null, array $hdrParams = [], array $bodyParams = [])
    {
        $this->table = $table;
        $this->title = $title;
        $this->hdrClass = $hdrClass;
        $this->bodyClass = $bodyClass;
        $this->hdrParams = $hdrParams;
        $this->bodyParams = $bodyParams;
    }

    /**
     * Set the title.
     * 
     * @param  string  $title   Title.
     * @return ColumnInterface 
     */
    public function setTitle(string $class): ColumnInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Add a header class.
     * 
     * @param   string  $class  Class to add.
     * @return  ColumnInterface
     */
    public function addHdrClass(string $class): ColumnInterface
    {
        if (null !== $this->hdrClass and '' != $this->hdrClass) {
            $this->hdrClass .= ' ';
        }
        $this->hdrClass .= $class;
        return $this;
    }

    /**
     * Set the header class.
     * 
     * @param   string  $class  Class to set.
     * @return  ColumnInterface
     */
    public function setHdrClass(string $class): ColumnInterface
    {
        $this->hdrClass = $class;
        return $this;
    }

    /**
     * Add a body class.
     * 
     * @param   string  $class  Class to add.
     * @return  ColumnInterface
     */
    public function addBodyClass(string $class): ColumnInterface
    {
        if (null !== $this->bodyClass and '' != $this->bodyClass) {
            $this->bodyClass .= ' ';
        }
        $this->bodyClass .= $class;
        return $this;
    }

    /**
     * Set the body class.
     * 
     * @param   string  $class  Class to set.
     * @return  ColumnInterface
     */
    public function setBodyClass(string $class): ColumnInterface
    {
        $this->bodyClass = $class;
        return $this;
    }

    /**
     * Render the header.
     * 
     * @return  string
     */
    public function renderHdr(): string
    {
        $params = $this->hdrParams;
        if ($this->hdrClass) {
            $params['class'] = $this->hdrParams;
        }

        $h = new Html($this->hdrTag, $params);
        return $h->render($this->title);
    }
}
