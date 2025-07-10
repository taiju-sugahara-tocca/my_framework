<?php

namespace Framework;

use App\DB\DBConnection;

use Framework\QueryBuilder;

abstract class Model{
    /**
     * テーブル名
     */
    abstract protected static function table(): string;

    /**
     * ソート可能なカラム（標準ソート）
     * ＊カスタム的なソートの場合はアプリケーション側で上書きする(QueryBuilderのsetSortable()を使用)
     */
    abstract protected static function standardSortable(): array;

    /**
     * クエリビルダを生成する
     */
    public static function query()
    {
        $query = new QueryBuilder();
        $query->from(static::table());
        $query->setSortable(static::standardSortable());
        return $query;
    }

    /**
     * 配列形式からインスタンス生成する
     */
    abstract protected static function createInstancefromArray(array $rows);

    /**
     * データ一覧を取得する
     */
    public static function getDatalist(array $rows): array
    {
        $data = array_map([static::class, 'createInstancefromArray'], $rows);
        return $data;
    }

    /**
     * データ１件を取得する
     */
    public static function getData(array $rows): ?Model
    {
        if (empty($rows)) {
            return null; // データがない場合はnullを返す
        }
        return static::createInstancefromArray($rows[0]);
    }

    /**
     * テーブル名のプレフィックスを持つカラムを抽出する
     * 例: 'post_data_' プレフィックスを持つカラムを抽出
     */
    public static function extractPrefixedArray(array $row): array {
        $prefix = static::table() . '_';
        $result = [];
        $len = strlen($prefix);
        foreach ($row as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $result[substr($key, $len)] = $value;
            }
        }
        return $result;
    }

}