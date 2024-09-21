<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Mock\DecryptableMock;
use Tests\Unit\Mock\DecryptableWithChangedMock;
use Tests\Unit\Mock\NotDecryptableWithChangedMock;

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
        \DB::Prepare('SELECT FROM', []);
    }

    public function test_query_on_bad_table(): void
    {
        $this->expectExceptionMessageMatches("/doesn't exist/");
        \DB::Prepare('DELETE FROM unexistant_table', []);
    }

    public function test_query_with_bad_parameter(): void
    {
        $this->thereIsATable('test_table', 'test VARCHAR(255)', [['test' => 'foo']]);

        $result = \DB::Prepare('update test_table set test = :test', ['foo' => 'bar']);

        $this->assertNull($result);
        $this->assertSame([['test' => 'foo']], $this->contentOfTable('test_table'));
    }

    public function test_fetch_all_class_decrypt(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, DecryptableMock::class);

        $this->assertInstanceOf(DecryptableMock::class, $results[0]);
        $this->assertSame('1', $results[0]->var);
        $this->assertTrue($results[0]->isDecrypted());
    }

    public function test_fetch_all_class_decrypt_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, DecryptableWithChangedMock::class);

        $this->assertInstanceOf(DecryptableWithChangedMock::class, $results[0]);
        $this->assertFalse($results[0]->isChanged());
    }

    public function test_fetch_class_decrypt(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, DecryptableMock::class);

        $this->assertInstanceOf(DecryptableMock::class, $results);
        $this->assertSame('1', $results->var);
        $this->assertTrue($results->isDecrypted());
    }

    public function test_fetch_class_decrypt_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, DecryptableWithChangedMock::class);

        $this->assertInstanceOf(DecryptableWithChangedMock::class, $results);
        $this->assertFalse($results->isChanged());
    }

    public function test_fetch_class_no_decrypt_do_not_set_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, NotDecryptableWithChangedMock::class);

        $this->assertInstanceOf(NotDecryptableWithChangedMock::class, $results);
        $this->assertNull($results->isChanged());
    }

    public function test_fetch_all_class_no_decrypt_do_not_set_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, NotDecryptableWithChangedMock::class);

        $this->assertInstanceOf(NotDecryptableWithChangedMock::class, $results[0]);
        $this->assertNull($results[0]->isChanged());
    }

    public function test_bad_database_configuration(): void
    {
        $this->thereIsABadDatabaseConfiguration();

        $this->expectExceptionMessageMatches('/failure in name resolution/');
        $result = \DB::Prepare('SELECT 1 AS test', []);
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

    private function thereIsABadDatabaseConfiguration(): void
    {
        global $CONFIG;
        $CONFIG['db']['host'] = 'badhost';
        $reflection = new \ReflectionClass(\DB::class);
        $reflection->setStaticPropertyValue('connection', null);
    }

}