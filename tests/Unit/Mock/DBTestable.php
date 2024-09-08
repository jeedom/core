<?php

namespace Tests\Unit\Mock;

class DBTestable extends \DB
{
    private static $queries = [];
    private static $tablesToOptimize = [];

    public static function &Prepare($_query, $_params, $_fetchType = self::FETCH_TYPE_ROW, $_fetch_param = \PDO::FETCH_ASSOC, $_fetch_opt = null)
    {
        self::$queries[] = [$_query, $_params];

        $res = parent::Prepare($_query, $_params, $_fetchType, $_fetch_param, $_fetch_opt);

        return $res;
    }

    public static function getOptimizationQueries(): array
    {
        $result = array_filter(self::$queries, static function ($query) {
            return 0 === strpos($query[0], 'OPTIMIZE TABLE');
        });
        self::$queries = [];

        return $result;
    }

    public static function addTableToOptimize(string $table): void
    {
        self::$tablesToOptimize[] = ['TABLE_NAME' => $table];
    }

    /**
     * @return array{TABLE_NAME: string}[]
     */
    protected static function getTablesToOptimize(): array
    {
        return self::$tablesToOptimize;
    }
}
