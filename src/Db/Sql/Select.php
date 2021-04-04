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
namespace GreenFedora\Db\Sql;

use GreenFedora\Db\Sql\Exception\DbSqlException;

use GreenFedora\Db\Sql\AbstractSql;
use GreenFedora\Db\Sql\SelectInterface;

use GreenFedora\Db\Driver\Stmt\Stmt;
use GreenFedora\Db\Driver\Stmt\StmtInterface;

use GreenFedora\Db\Sql\Part\TableName;
use GreenFedora\Db\Sql\Part\ColumnNameCollection;
use GreenFedora\Db\Sql\Part\ValueCollection;
use GreenFedora\Db\Sql\Part\WhereUser;
use GreenFedora\Db\Sql\Part\Where;
use GreenFedora\Db\Sql\Part\HavingUser;
use GreenFedora\Db\Sql\Part\Having;
use GreenFedora\Db\Sql\Part\LimitUser;
use GreenFedora\Db\Sql\Part\Limit;
use GreenFedora\Db\Sql\Part\OrderUser;
use GreenFedora\Db\Sql\Part\Order;
use GreenFedora\Db\Sql\Part\GroupUser;
use GreenFedora\Db\Sql\Part\Group;
use GreenFedora\Db\Sql\Part\TableReference;
use GreenFedora\Db\Sql\Part\SelectExpr;
use GreenFedora\Db\Sql\Part\Join;

use GreenFedora\Db\DbInterface;

/**
 * SQL select class.
 */
class Select extends AbstractSql implements SelectInterface
{
    use WhereUser;
    use LimitUser;
    use GroupUser;
    use OrderUser;
    use HavingUser;

    /**
     * Select expressions.
     * @var SelectExpr[]
     */
    protected $selectExprs = array();

    /**
     * Froms.
     * @var TableReference[]
     */
    protected $from = array();

    /**
     * Joins.
     * @var Join[]
     */
    protected $joins = array();

    /**
     * Constructor.
     *
     * @param   DbInterface              $db         Database parent.
     * @param   string|null              $from       From table.
     * @return  void
     */
    public function __construct(DbInterface $db, ?string $from = null)
    {
        parent::__construct($db);
        $this->where = new Where($db);
        $this->limit = new Limit();
        $this->order = new Order($db);
        $this->group = new Group($db);
        $this->having = new Having($db);

        if (null !== $from) {
            $this->from($from);
        }
    }

    /**
     * Add a from.
     *
     * @param   string      $table      Table.
     * @param   string|null $alias      Table alias.
     * @return  SelectInterface
     */
    public function from(string $table, ?string $alias = null): SelectInterface
    {
        $this->from[] = new TableReference($this->db, $table, $alias);
        return $this;
    }

    /**
     * Add a join.
     *
     * @param   string  $toTable        To table.
     * @param   string  $toColumn       To name.
     * @param   string  $fromTable      From table.
     * @param   string  $fromColumn     From name.
     * @param   string  $alias          Alias.
     * @param   string  $comp           Comparison operator.
     * @param   string  $type           Join type.
     * @return  SelectInterface
     */
    public function join(
        string $toTable,
        string $toColumn,
        string $fromTable,
        string $fromColumn,
        ?string $alias = null,
        string $comp = '=',
        string $type = 'left'
    ): SelectInterface 
    {
        $this->joins[] = new Join($this->db, $toTable, $toColumn, $fromTable, $fromColumn, $alias, $comp, $type);
        return $this;
    }

    /**
     * Add a column based select expression.
     *
     * @param   string|array    $cols       Columns.
     * @param   string|null     $alias      Alias.
     * @return  SelectInterface
     */
    public function col($cols, ?string $alias = null): SelectInterface
    {
        if (is_array($cols)) {
            foreach ($cols as $col => $ali) {
                if (is_int($col)) {
                    $this->selectExprs[] = new SelectExpr($this->db, $ali, null, 'col');
                } else {
                    $this->selectExprs[] = new SelectExpr($this->db, $col, $ali, 'col');
                }
            }
        } else {
            $this->selectExprs[] = new SelectExpr($this->db, $cols, $alias, 'col');
        }
        return $this;
    }

    /**
     * Clear the column selection.
     * 
     * @return  SelectInterface
     */
    public function clearCols(): SelectInterface
    {
        $this->selectExprs = array();
        return $this;
    }

    /**
     * Expr.
     *
     * @param   string          $expr       Expression.
     * @param   string|null     $alias      Alias.
     * @return  SelectInterface
     */
    public function expr(string $expr, ?string $alias = null): SelectInterface
    {
        $this->selectExprs[] = new SelectExpr($this->db, $expr, $alias, 'expr');
        return $this;
    }

    /**
     * Count aggregate.
     *
     * @param   string          $col        Column.
     * @param   string|null     $alias      Alias.
     * @return  SelectInterface
     */
    public function count(string $col, ?string $alias = null): SelectInterface
    {
        $this->selectExprs[] = new SelectExpr($this->db, $col, $alias, 'col', 'count');
        return $this;
    }

    /**
     * Year aggregate.
     *
     * @param   string          $col        Column.
     * @param   string|null     $alias      Alias.
     * @return  SelectInterface
     */
    public function year(string $col, ?string $alias = null): SelectInterface
    {
        $this->selectExprs[] = new SelectExpr($this->db, $col, $alias, 'col', 'year');
        return $this;
    }

    /**
     * Month aggregate.
     *
     * @param   string          $col        Column.
     * @param   string|null     $alias      Alias.
     * @return  SelectInterface
     */
    public function month(string $col, ?string $alias = null): SelectInterface
    {
        $this->selectExprs[] = new SelectExpr($this->db, $col, $alias, 'col', 'month');
        return $this;
    }

    /**
     * Week aggregate.
     *
     * @param   string          $col        Column.
     * @param   string|null     $alias      Alias.
     * @return  SelectInterface
     */
    public function week(string $col, ?string $alias = null): SelectInterface
    {
        $this->selectExprs[] = new SelectExpr($this->db, $col, $alias, 'col', 'week');
        return $this;
    }

    /**
     * Get the SQL.
     *
     * @return  string
     * @throws  DbSqlException
     */
    public function getSql() : string
    {
        $ret = 'SELECT ';

        // Expressions.
        if (count($this->selectExprs)) {
            $exprs = array();
            foreach ($this->selectExprs as $expr) {
                $exprs[] = $expr->resolve();
            }
            $ret .= implode(',', $exprs);
        } else {
            $ret .= '*';
        }

        // Froms.
        if (count($this->from)) {
            $froms = array();
            foreach ($this->from as $from) {
                $froms[] = $from->resolve();
            }
            $ret .= ' FROM ' . implode(',', $froms);
        } else {
            throw new DbSqlException("No 'FROM' specfied or SELECT statement");
        }

        // Joins.
        if (count($this->joins)) {
            $joins = array();
            foreach ($this->joins as $join) {
                $joins[] = $join->resolve();
            }
            $ret .= implode('', $joins);
        }

        // Where's
        $ret .= $this->where->resolve();

        // Group.
        $ret .= $this->group->resolve();

        // Having.
        $ret .= $this->having->resolve();

        // Order.
        $ret .= $this->order->resolve();

        // Limit.
        $ret .= $this->limit->resolve();

        // Return
        return $ret;
    }

    /**
     * Prepare.
     *
     * @return  StmtInterface
     */
    public function prepare() : StmtInterface
    {
        $stmt = $this->db->prepare($this->getSql());
        if ($stmt) {
            $this->where->bind($stmt);
            $this->having->bind($stmt);
        }
        return $stmt;
    }

    /**
     * Fetch a column.
     *
     * @param   int         $offset         Offset.
     * @return  mixed
     */
    public function fetchColumn(int $offset = 0)
    {
        return $this->prepare()->fetchColumn($offset);
    }

    /**
     * Fetch an array of data.
     *
     * @return  array
     */
    public function fetchArray() : array
    {
        return $this->prepare()->fetchArray();
    }
}
