<?php

namespace Unit;

use PHPUnit\Framework\TestCase;

final class DBTest extends TestCase
{
    public function test_a_simple_query(): void
    {
        $result = \DB::Prepare('SELECT 1 AS test', []);

        $this->assertSame(['test' => '1'], $result);
    }

    public function test_a_simple_query_with_parameters(): void
    {
        $result = \DB::Prepare('SELECT :test AS test', ['test' => '1']);

        $this->assertSame(['test' => '1'], $result);
    }

    public function test_fetch_all(): void
    {
        $result = \DB::Prepare('SELECT 1 AS test', [], \DB::FETCH_TYPE_ALL);

        $this->assertSame([['test' => '1']], $result);
    }

    public function test_fetch_class(): void
    {
        $result = \DB::Prepare('SELECT 1 AS test', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, \stdClass::class);

        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertSame('1', $result->test);
    }

    public function test_fetch_all_class(): void
    {
        $results = \DB::Prepare('SELECT 1 AS test', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, \stdClass::class);

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertInstanceOf(\stdClass::class, $results[0]);
        $this->assertSame('1', $results[0]->test);
    }

    public function test_insert(): void
    {
        $this->thereIsATable('test_table', 'test VARCHAR(255)');

        $result = \DB::Prepare('INSERT INTO test_table (test) VALUES (:test)', ['test' => 'foo']);

        $this->assertFalse($result); // TODO: Est-ce qu’un return du nombre de lignes insérées ne serait pas plus intéressant ?
        $this->assertSame([
            ['test' => 'foo']
        ], $this->contentOfTable('test_table'));
    }

    public function test_update(): void
    {
        $this->thereIsATable('test_table', 'test VARCHAR(255)', [['test' => 'foo']]);

        $result = \DB::Prepare('UPDATE test_table SET test = :test WHERE test = :old', ['test' => 'bar', 'old' => 'foo']);

        $this->assertNull($result); // TODO: Est-ce qu’un return du nombre de lignes modifiées ne serait pas plus intéressant ?
        $this->assertSame([
            ['test' => 'bar']
        ], $this->contentOfTable('test_table'));
    }

    public function test_delete(): void
    {
        $this->thereIsATable('test_table', 'test VARCHAR(255)', [['test' => 'foo']]);

        $result = \DB::Prepare('DELETE FROM test_table WHERE test = :old', ['old' => 'foo']);

        $this->assertNull($result); // TODO: Est-ce qu’un return du nombre de lignes supprimées ne serait pas plus intéressant ?
        $this->assertSame([], $this->contentOfTable('test_table'));
    }

    public function test_bad_sql_syntax(): void
    {
        $this->expectExceptionMessageMatches('/syntax/');
        \DB::Prepare('plop', []);
    }

    private function thereIsATable(string $tableName, string $structure, array $data = []): void
    {
        $connection = \DB::getConnection();
        $connection->exec('DROP TABLE IF EXISTS ' . $tableName);
        $connection->exec('CREATE TABLE ' . $tableName . ' (' . $structure . ')');

        foreach ($data as $row) {
            \DB::Prepare('INSERT INTO ' . $tableName . ' (' . implode(', ', array_keys($row)) . ') VALUES (' . implode(', ', array_map(function ($value) { return ':' . $value; }, array_keys($row))) . ')', $row, \DB::FETCH_TYPE_ALL);
        }
    }

    private function contentOfTable(string $table): array
    {
        return \DB::Prepare('SELECT * FROM '. $table, [], \DB::FETCH_TYPE_ALL);
    }

}