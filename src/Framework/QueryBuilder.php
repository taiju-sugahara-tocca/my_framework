<?php

namespace Framework;

use App\DB\DBConnection;

class QueryBuilder{

    /**
     * Select条件
     */
    protected $selectCondition = "";

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
     * 生のSQL条件
     */
    protected $rawCondition = "";

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
     * @param string $columnList
     */
    public function select(string $columnList)
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
     * 生のSQLを実行する
     */
    public function raw(string $sql)
    {
        $this->rawCondition = $sql;
        return $this;
    }

    /**
     * select実行
     */
    protected function selectExec()
    {
        $sql = "";
        if( !empty($this->selectCondition) ) {
            $sql .= "SELECT " . $this->selectCondition . " ";
        } else {
            $sql .= "SELECT * " ;
        }
        $sql .= " FROM " . $this->fromCondition . " ";
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
        $this->whereExec();
        $this->whereInExec();
        $this->likeExec();
        $this->orderByExec();
        $this->limitExec();
        $this->offsetExec(); //offsetはlimitの後に実行する必要がある


        //[TODO] Raw SQLの実行
        //[TODO] Inner Joinの実行
        //[TODO] Left Joinの実行

        // if( !empty($this->innerJoinCondition) ) {
        //     foreach ($this->innerJoinCondition as $join) {
        //         $sql .= " INNER JOIN " . $join['table'] . " ON " . $join['condition'] . " ";
        //     }
        // }
        
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