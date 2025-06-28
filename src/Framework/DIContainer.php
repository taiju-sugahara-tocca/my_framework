<?php

namespace Framework;

/**
 * DIコンテナのクラス
 */
class DIContainer{
    private array $bindings = [];

    /**
     * コンテナにサービスを登録するメソッド
     */
    public function bind(string $abstract, string|callable $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * コンテナからサービスを取得するメソッド
     */
    public function make(string $abstract)
    {
        //bindされている場合（interface）はこの中に入る。以降の処理には入らない。
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            if (is_callable($concrete)) {
                // ここで $this（コンテナ自身）を渡す
                return $concrete($this);
            }
            //再帰的にmake
            return $this->make($concrete);
        }

        // bindされていない場合(class)は、クラス名を直接使用してインスタンス化を試みる。以降の処理に入る。
        $reflection = new \ReflectionClass($abstract);
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return new $abstract();
        }

        // コンストラクタの引数を解決する
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) { //型が指定されていて、かつ組み込み型（int, stringなど）でなければ
                $params[] = $this->make($type->getName());//その型（クラスやインターフェース）をDIコンテナで解決し、インスタンスを引数として渡す
            } else {
                // デフォルト値があれば使う
                if ($param->isDefaultValueAvailable()) {
                    $params[] = $param->getDefaultValue();
                } else { // デフォルト値がない場合は例外を投げる
                    throw new \Exception("Cannot resolve parameter \${$param->getName()} for {$abstract}");
                }
            }
        }
        return $reflection->newInstanceArgs($params);// 依存を解決した引数リストでインスタンスを生成
    }

}