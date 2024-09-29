<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Mock\DBTestable;
use Tests\Unit\Mock\ObjectMock\DecryptableAndChangableMock;
use Tests\Unit\Mock\ObjectMock\DecryptableMock;
use Tests\Unit\Mock\ObjectMock\DecryptableWithChangedMock;
use Tests\Unit\Mock\ObjectMock\ChangableMock;
use Tests\Unit\Mock\ObjectMock\ObjectMock;
use Tests\Unit\Mock\ObjectMock\ObjectWithIdField;
use Tests\Unit\Mock\ObjectMock\ObjectWithPreInsert;
use Tests\Unit\Mock\ObjectMock\ObjectWithPreSave;

final class DBTest extends TestCase
{
    /**
     * @var callable|null
     */
    private $originalErrorHandler = null;

    private $inTransaction = false;

    /**
     * @var array{db: array{dsn: string, user: string, password: string, options: array{}}}|null
     */
    private $originalConfig = null;

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

        $this->assertNull($result); // TODO: throw exception ?
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
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, DecryptableAndChangableMock::class);

        $this->assertInstanceOf(DecryptableAndChangableMock::class, $results[0]);
        $this->assertFalse($results[0]->isChanged());
    }

    public function test_fetch_class_decrypt(): void
    {
        $results = \DB::Prepare('SELECT 1 AS publicVar', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, DecryptableMock::class);

        $this->assertInstanceOf(DecryptableMock::class, $results);
        $this->assertSame('1', $results->publicVar);
        $this->assertTrue($results->isDecrypted());
    }

    public function test_fetch_class_decrypt_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, DecryptableAndChangableMock::class);

        $this->assertInstanceOf(DecryptableAndChangableMock::class, $results);
        $this->assertFalse($results->isChanged());
    }

    public function test_fetch_class_no_decrypt_do_not_set_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, ChangableMock::class);

        $this->assertInstanceOf(ChangableMock::class, $results);
        $this->assertNull($results->isChanged());
    }

    public function test_fetch_all_class_no_decrypt_do_not_set_changed(): void
    {
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, ChangableMock::class);

        $this->assertInstanceOf(ChangableMock::class, $results[0]);
        $this->assertNull($results[0]->isChanged());
    }

    public function test_bad_database_configuration(): void
    {
        $this->thereIsABadDatabaseConfiguration();

        $this->expectExceptionMessageMatches('/failure in name resolution/');
        \DB::Prepare('SELECT 1 AS test', []);
    }

    public function test_can_not_clone_object(): void
    {
        $db = new \DB();

        $this->expectPhpError('DB : Cloner cet objet n\'est pas permis');

        $clone = clone $db;
    }

    public function test_no_need_to_optimize_database(): void
    {
        DBTestable::optimize();

        $optimizationQueries = DBTestable::getOptimizationQueries();
        $this->assertCount(0, $optimizationQueries);
    }

    public function test_optimize_database(): void
    {
        $this->thereIsATable('test_table', 'test VARCHAR(255)');
        DBTestable::addTableToOptimize('test_table');

        DBTestable::optimize();

        $optimizationQueries = DBTestable::getOptimizationQueries();
        $this->assertCount(1, $optimizationQueries);
    }

    public function test_save_data(): void
    {
        $object = new ObjectMock();
        $this->thereIsATableForObject($object);

        \DB::save($object);

        $this->assertCount(1, $this->contentOfTable($object->getTableName()));

    }

    public function test_call_presave_hook(): void
    {
        $object = new ObjectWithPreSave();
        $this->thereIsATableForObject($object);

        \DB::save($object);

        $this->assertTrue($object->isPreSaveCalled());
    }

    public function test_skip_presave_hook_with_direct_flag(): void
    {
        $object = new ObjectWithPreSave();
        $this->thereIsATableForObject($object);

        \DB::save($object, true);

        $this->assertFalse($object->isPreSaveCalled());
    }

    public function test_save_set_private_id(): void
    {
        $object = new ObjectWithIdField();
        $this->thereIsATableForObject($object);

        \DB::save($object);

        $this->assertTrue($object->isIdSet());
    }

    public function test_call_preinsert_hook(): void
    {
        $object = new ObjectWithPreInsert();
        $this->thereIsATableForObject($object);

        \DB::save($object);

        $this->assertTrue($object->isPreInsertCalled());
    }

    public function test_skip_preinsert_hook_with_direct_flag(): void
    {
        $object = new ObjectWithPreInsert();
        $this->thereIsATableForObject($object);

        \DB::save($object, true);

        $this->assertFalse($object->isPreInsertCalled());
    }


    /**
     * @before
     */
    protected function beginTransaction(): void
    {
        $connection = \DB::getConnection();
        $connection->beginTransaction();
        $this->inTransaction = true;
    }

    /**
     * @after
     */
    protected function rollback(): void
    {
        if (false === $this->inTransaction) {
            return;
        }

        $connection = \DB::getConnection();
        $connection->rollBack();
        $this->inTransaction = false;

    }

    protected function setUp(): void
    {
        global $CONFIG;
        $this->originalConfig = $CONFIG;
    }

    protected function tearDown(): void
    {
        if (null !== $this->originalErrorHandler) {
            set_error_handler($this->originalErrorHandler);
            $this->originalErrorHandler = null;
        }

        global $CONFIG;
        $CONFIG = $this->originalConfig;
    }

    private function thereIsATable(string $tableName, string $structure, array $data = []): void
    {
        $connection = \DB::getConnection();
        $connection->exec('DROP TABLE IF EXISTS ' . $tableName);
        $connection->exec('CREATE TABLE ' . $tableName . ' (' . $structure . ')');
        if ('00000' !== $connection->errorCode()) {
            $this->fail($connection->errorInfo()[2]);
        }

        foreach ($data as $row) {
            $statement = $statement ?? $connection->prepare('INSERT INTO ' . $tableName . ' (' . implode(', ', array_keys($row)) . ') VALUES (' . implode(', ', array_map(function ($value) {
                    return ':' . $value;
                }, array_keys($row))) . ')');
            $statement->execute($row);
        }
    }

    private function contentOfTable(string $table): array
    {
        $connection = \DB::getConnection();

        $statement = $connection->query('SELECT * FROM ' . $table);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function thereIsABadDatabaseConfiguration(): void
    {
        $this->rollback();

        global $CONFIG;
        $CONFIG['db']['host'] = 'badhost';
        $reflection = new \ReflectionClass(\DB::class);
        $reflection->setStaticPropertyValue('connection', null);
    }

    private function expectPhpError(string $message): void
    {
        $this->rollback();
        $this->originalErrorHandler = set_error_handler(function (int $errno, string $errstr) use ($message): void {
            $this->assertSame(E_USER_ERROR, $errno);
            $this->assertEquals($message, $errstr);
        });
    }

    private function thereIsATableForObject(ObjectMock $object): void
    {
        $this->thereIsATable($object->getTableName(), $object->getTableStructure());
    }
}