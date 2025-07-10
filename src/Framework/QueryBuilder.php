<?php

namespace Framework;

use App\DB\DBConnection;

class QueryBuilder{

    /**
     * Select条件
     */
    protected $selectCondition = [];

    /**
     * Select Raw条件
     */
    protected $selectRawCondition = "";

    /**
     * From条件
     */
    protected $fromCondition = "";

    /**
     * Inner Join条件
     */
    protected $innerJoinCondition = [];

    /**
     * Left Join条件
     */
    protected $leftJoinCondition = [];

    /**
     * Where条件
     */
    protected $whereCondition = []; 

    /**
     * WhereIn条件
     */
    protected $whereInCondition = [];

    /**
     * Where Like条件
     */
    protected $likeCondition = [];

    /**
     * Group By条件
     */
    protected $groupByCondition = [];

    /**
     * Having条件
     */
    protected $havingCondition = [];

    /**
     * Offset条件
     */
    protected $offsetCondition = 0;

    /**
     * Limit条件
     */
    protected $limitCondition = 0;

    /**
     * Order By条件
     */
    protected $orderByColumnCondition = [];

    /**
     * ソート可能カラム
     */
    protected $sortable = [];

    /**
     * SQL
     */
    protected $sql = "";

    /**
     * 初めてのwhere句かどうか
     */
    protected $firstWhere = true;

    /**
     * 初めてのorder by句かどうか
     */
    protected $firstOrderBy = true;

    /**
     * プレイスホルダーのバインドパラメータ
     */
    protected $bindParams = [];

    const MAX_LIMIT_NUM = 99999999;

    /**
     * $sortableを設定する
     * @param array $sortable
     */
    public function setSortable(array $sortable)
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * select句を使ってデータを取得する
     * @param array $columnList
     */
    public function select(array $columnList)
    {
        $this->selectCondition = $columnList;
        return $this;
    }

    /**
     * from句を使ってデータを取得する
     */
    public function from(string $tableName)
    {
        $this->fromCondition = $tableName;
        return $this;
    }

    /**
     * where句を使ってデータを取得する
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function where(string $column, string $operator, $value)
    {
        $this->whereCondition[] = [$column, $operator, $value];
        return $this;
    }

    /**
     * where in句を使ってデータを取得する
     * @param string $column
     * @param array $values
     */
    public function whereIn(string $column, array $values)
    {
        $this->whereInCondition[] = [$column, $values];
        return $this;
    }

    /**
     * where Like句を使ってデータを取得する
     */
    public function like(string $column, string $value, string $type = 'both')
    {
        switch ($type) {
        case 'prefix':
            $value = $value . '%';
            break;
        case 'suffix':
            $value = '%' . $value;
            break;
        case 'both':
        default:
            $value = '%' . $value . '%';
            break;
        }

        $this->likeCondition[] = [$column, $value];
        return $this;
    }

    /**
     * group by句を使ってデータを取得する
     * @param array $columns
     */
    public function groupBy(array $columns)
    {
        $this->groupByCondition = $columns;
        return $this;
    }

    /**
     * having句を使ってデータを取得する
     * @param string $column
     * @param string $operator
     * @param mixed $value
     */
    public function having(string $column, string $operator, $value)
    {
        $this->havingCondition[] = [$column, $operator, $value];
        return $this;
    }

    /**
     * offset句を使ってデータを取得する
     * @param int $offset
     */
    public function offset(int $offset)
    {
        $this->offsetCondition = $offset;
        return $this;
    }

    /**
     * limit句を使ってデータを取得する
     * @param int $limit
     */
    public function limit(int $limit)
    {
        $this->limitCondition = $limit;
        return $this;
    }

    /**
     * order by句を使ってデータを取得する
     * @param string $column
     * @param string $direction
     */
    public function orderBy(string $column, string $direction = 'asc')
    {
        $this->orderByColumnCondition[] = [$column, $direction];
        return $this;
    }

    /**
     * inner join句を使ってデータを取得する
     * @param string $table
     * @param string $condition
     */
    public function innerJoin(string $table, string $condition)
    {
        $this->innerJoinCondition[] = [$table, $condition];
        return $this;
    }

    /**
     * left join句を使ってデータを取得する
     * @param string $table
     * @param string $condition
     */
    public function leftJoin(string $table, string $condition)
    {
        $this->leftJoinCondition[] = [$table, $condition];
        return $this;
    }

    /**
     * select Raw句を使ってデータを取得する
     * @param string $raw
     */
    public function selectRaw(string $raw)
    {
        $this->selectRawCondition = $raw;
        return $this;
    }

    /**
     * select実行
     */
    protected function selectExec()
    {
        $sql = "SELECT ";
        $columns = [];

        if (!empty($this->selectCondition)) {
            $columns[] = implode(", ", $this->selectCondition);
        }
        if (!empty($this->selectRawCondition)) {
            $columns[] = $this->selectRawCondition;
        }
        if (empty($columns)) {
            $sql .= "* ";
        } else {
            $sql .= implode(", ", $columns) . " ";
        }
        $sql .= "FROM " . $this->fromCondition . " ";
        $this->sql = $sql;
    }

    /**
     * where実行
     */
    protected function whereExec()
    {
        if( !empty($this->whereCondition) ) {
            foreach ($this->whereCondition as $index => $condition) {
                $column = $condition[0];
                $operator = $condition[1];
                $value = $condition[2]; 
                if( $this->firstWhere ) {
                    $this->sql .= " WHERE ";
                    $this->firstWhere = false;
                } else {
                    $this->sql .= " AND ";
                }
                $this->sql .= " $column $operator ? ";
                $this->bindParams[] = $value;
            }
        }
    }

    /**
     * where in実行
     */
    protected function whereInExec()
    {
        if( !empty($this->whereInCondition) ) {
            foreach ($this->whereInCondition as $condition) {
                $column = $condition[0];
                $values = $condition[1];
                if( $this->firstWhere ) {
                    $this->sql .= " WHERE ";
                    $this->firstWhere = false;
                } else {
                    $this->sql .= " AND ";
                }
                $this->sql .= " $column IN (" . implode(',', array_fill(0, count($values), '?')) . ") ";
                foreach ($values as $value) {
                    $this->bindParams[] = $value;
                }
            }
        }
    }

    /**
     * where lile実行
     */
    protected function likeExec()
    {
        if( !empty($this->likeCondition) ) {
            foreach ($this->likeCondition as $condition) {
                $column = $condition[0];
                $value = $condition[1];
                if( $this->firstWhere ) {
                    $this->sql .= " WHERE ";
                    $this->firstWhere = false;
                } else {
                    $this->sql .= " AND ";
                    
                }
                $this->sql .= " $column LIKE ? ";
                $this->bindParams[] = $value;
            }
        }
    }

    /**
     * group by実行
     */
    protected function groupByExec()
    {
        if( !empty($this->groupByCondition) ) {
            $this->sql .= " GROUP BY " . implode(", ", $this->groupByCondition) . " ";
        }
    }

    /**
     * having実行
     */
    protected function havingExec()
    {
        if( !empty($this->havingCondition) ) {
            foreach ($this->havingCondition as $index => $condition) {
                $column = $condition[0];
                $operator = $condition[1];
                $value = $condition[2];
                if( $index === 0 ) {
                    $this->sql .= " HAVING ";
                } else {
                    $this->sql .= " AND ";
                } 
                $this->sql .= " $column $operator ? ";
                $this->bindParams[] = $value;
            }
        }
    }


    /**
     * offset実行
     */
    protected function offsetExec()
    {
        if(!empty($this->offsetCondition)) {
            if(empty($this->limitCondition)){
                $this->sql .= " LIMIT " . self::MAX_LIMIT_NUM . " ";
            }
            $this->sql .= " OFFSET " .  $this->offsetCondition  . " ";
        }
    }

    /**
     * limit実行
     */
    protected function limitExec()
    {
        if(!empty($this->limitCondition)) {
            $this->sql .= " LIMIT " . $this->limitCondition . " ";
        }
    }
    
    /**
     * order by実行
     */
    protected function orderByExec()
    {
        if(!empty($this->orderByColumnCondition)) {
            foreach ($this->orderByColumnCondition as $condition) {
                $column = $condition[0];
                $direction = strtoupper($condition[1]) === 'DESC' ? 'DESC' : 'ASC';
                // ソート可能なカラムに含まれていない場合はスキップ
                if( !in_array($column, $this->sortable) ) {
                    continue;
                }
                if( $this->firstOrderBy ) {
                    $this->sql .= " ORDER BY ";
                    $this->firstOrderBy = false;
                }else {
                    $this->sql .= ", ";
                }
                $this->sql .= "$column $direction ";
            }
        }
    }

    /**
     * Inner Join実行
     */
    protected function innerJoinExec()
    {
        if( !empty($this->innerJoinCondition) ) {
            foreach ($this->innerJoinCondition as $join) {
                $table = $join[0];
                $condition = $join[1];
                $this->sql .= " INNER JOIN " . $table . " ON " . $condition . " ";
            }
        }
    }

    /**
     * Left Join実行
     */
    protected function leftJoinExec()
    {
        if( !empty($this->leftJoinCondition) ) {
            foreach ($this->leftJoinCondition as $join) {
                $table = $join[0];
                $condition = $join[1];
                $this->sql .= " LEFT JOIN " . $table . " ON " . $condition . " ";
            }
        }
    }

    /**
     * Insert実行
     * @param array $data
     */
    protected function insertExec(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = array_values($data);
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $this->sql = "INSERT INTO " . $this->fromCondition . " ($columns) VALUES ($placeholders)";
        $this->bindParams = $values;
    }

    /**
     * Update実行
     * @param array $data
     */
    protected function updateExec(array $data)
    {
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = ?";
        }
        $setClause = implode(", ", $setClause);
        $values = array_values($data);
        $this->sql = "UPDATE " . $this->fromCondition . " SET $setClause ";
        $this->bindParams = $values;
    }

    /**
     * Delete実行
     */
    protected function deleteExec()
    {
        $this->sql = "DELETE FROM " . $this->fromCondition . " ";
    }


    /**
     * データを取得する
     */
    public function get(): array
    {
        $db = DBConnection::getConnection();
        $this->selectExec();
        $this->innerJoinExec();
        $this->leftJoinExec();
        $this->whereExec();
        $this->whereInExec();
        $this->groupByExec();
        $this->havingExec();
        $this->likeExec();
        $this->orderByExec();
        $this->limitExec();
        $this->offsetExec(); //offsetはlimitの後に実行する必要がある

        //[TODO] Raw SQLの実行・・・selectRawやorderByRawなどセクションごとのRawを作る設計にする（case文はselectなどに埋め込む形）。全体のRawは作成しない。
        //selectRawだけ実装すればとりあえずよしとする。
        
        $stm= $db->prepare($this->sql);
        $stm->execute($this->bindParams);
        $rows = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * データを登録する
     */
    public function insert(array $data)
    {
        $db = DBConnection::getConnection();
        $this->insertExec($data);
        
        $stm = $db->prepare($this->sql);
        $stm->execute($this->bindParams);
        
        return $db->lastInsertId();
    }

    /**
     * データを更新する
     */
    public function update(array $data)
    {
        if(empty($this->whereCondition) && empty($this->whereInCondition)) {
            throw new \Exception("更新条件が必要です。");
        }
        $db = DBConnection::getConnection();
        $this->updateExec($data);
        
        $this->whereExec();
        $this->whereInExec();

        $stm = $db->prepare($this->sql);
        $stm->execute($this->bindParams);
    }

    /**
     * データを削除する
     */
    public function delete()
    {
        if(empty($this->whereCondition) && empty($this->whereInCondition)) {
            throw new \Exception("削除条件が必要です。");
        }
        $db = DBConnection::getConnection();
        $this->deleteExec();

        $this->whereExec();
        $this->whereInExec();

        $stm = $db->prepare($this->sql);
        $stm->execute($this->bindParams);
    }


}