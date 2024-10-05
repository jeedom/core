<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Mock\DBTestable;
use Tests\Unit\Mock\ObjectMock\ObjectMock;

final class DBTest extends TestCase
{
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
        $class = $this->thereIsAnObject()->withHook('decrypt')->className();
        $results = \DB::Prepare('SELECT 1 AS var', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, $class);

        $this->assertInstanceOf($class, $results[0]);
        $this->assertSame('1', $results[0]->var);
        $this->assertTrue($results[0]->isMethodCalled('decrypt'));
    }

    public function test_fetch_all_class_decrypt_changed(): void
    {
        $class = $this->thereIsAnObject()->changeable()->withHook('decrypt')->className();
        $results = \DB::Prepare('SELECT 1 AS publicVar', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, $class);

        $this->assertInstanceOf($class, $results[0]);
        $this->assertTrue($results[0]->isMethodCalled('setChanged'));
        $this->assertFalse($results[0]->isChanged());
    }

    public function test_fetch_class_decrypt(): void
    {
        $class = $this->thereIsAnObject()->withHook('decrypt')->className();
        $results = \DB::Prepare('SELECT 1 AS publicVar', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, $class);

        $this->assertInstanceOf($class, $results);
        $this->assertSame('1', $results->publicVar);
        $this->assertTrue($results->isMethodCalled('decrypt'));
    }

    public function test_fetch_class_decrypt_changed(): void
    {
        $class = $this->thereIsAnObject()->changeable()->withHook('decrypt')->className();
        $results = \DB::Prepare('SELECT 1 AS publicVar', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, $class);

        $this->assertInstanceOf($class, $results);
        $this->assertTrue($results->isMethodCalled('setChanged'));
        $this->assertFalse($results->isChanged());
    }

    public function test_fetch_class_no_decrypt_do_not_set_changed(): void
    {
        $class = $this->thereIsAnObject()->changeable()->className();
        $results = \DB::Prepare('SELECT 1 AS publicVar', [], \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, $class);

        $this->assertInstanceOf($class, $results);
        $this->assertNull($results->isChanged());
    }

    public function test_fetch_all_class_no_decrypt_do_not_set_changed(): void
    {
        $class = $this->thereIsAnObject()->changeable()->className();
        $results = \DB::Prepare('SELECT 1 AS publicVar', [], \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, $class);

        $this->assertInstanceOf($class, $results[0]);
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
        $object = $this->thereIsAnObject();
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertCount(1, $this->contentOfTable($object->getTableName()));
    }

    public static function hookProvider(): iterable
    {
        yield from self::hookCalledWithDirectFlagProvider();
        yield from self::hookSkippedWithDirectFlagProvider();
    }

    /**
     * @dataProvider hookProvider
     */
    public function test_save_call_hooks(string $hook): void
    {
        $object = $this->thereIsAnObject()->withHook($hook);
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertTrue($object->isMethodCalled($hook));
    }

    public static function hookSkippedWithDirectFlagProvider(): iterable
    {
        yield ['preSave'];
        yield ['preInsert'];
        yield ['postInsert'];
        yield ['postSave'];
    }

    /**
     * @dataProvider hookSkippedWithDirectFlagProvider
     */
    public function test_save_skip_hook_with_direct_flag(string $hook): void
    {
        $object = $this->thereIsAnObject()->withHook($hook);
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object, true);

        $this->assertFalse($object->isMethodCalled($hook));
    }

    public function test_save_set_private_id(): void
    {
        $object = $this->thereIsAnObject()->withField('id');
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertTrue($object->isIdSet());
    }

    /**
     * @dataProvider hookProvider
     */
    public function test_save_skip_call_hook_on_object_without_method(string $hook): void
    {
        $object = $this->thereIsAnObject()->withoutMethod($hook);
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertFalse($object->isMethodCalled($hook));
    }

    public static function hookCalledWithDirectFlagProvider(): iterable
    {
        yield ['encrypt'];
        yield ['decrypt'];
    }

    /**
     * @dataProvider hookCalledWithDirectFlagProvider
     */
    public function test_save_call_hook_with_direct_flag(string $hook): void
    {
        $object = $this->thereIsAnObject()->withHook($hook);
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object, true);

        $this->assertTrue($object->isMethodCalled($hook));
    }

    public function test_insert_with_duplicate_unique_field_fail(): void
    {
        $object = $this->thereIsAnObject()->withUniqueField();
        $this->thereIsAnEmptyTableForObject($object);
        \DB::save($object);

        $this->expectException(\Exception::class);
        \DB::save($object);
    }

    public function test_replace_with_duplicate_unique_field(): void
    {
        $object = $this->thereIsAnObject()->withUniqueField();
        $this->thereIsAnEmptyTableForObject($object);
        \DB::save($object);

        \DB::save($object, false, true);

        $this->assertCount(1, $this->contentOfTable($object->getTableName()));
    }

    public function test_insert_hooks_order(): void
    {
        $object = $this->thereIsAnObject()
            ->withHooks('preSave', 'postSave', 'preInsert', 'postInsert', 'preUpdate', 'postUpdate', 'encrypt', 'decrypt')
        ;
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertSame([
            'preSave',
            'preInsert',
            'encrypt',
            'decrypt',
            'postInsert',
            'postSave',
        ], $object->getMethodsCalled());
    }

    public function test_insert_direct_hooks_order(): void
    {
        $object = $this->thereIsAnObject()
            ->withHooks('preSave', 'postSave', 'preInsert', 'postInsert', 'preUpdate', 'postUpdate', 'encrypt', 'decrypt')
        ;
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object, true);

        $this->assertSame([
            'encrypt',
            'decrypt',
        ], $object->getMethodsCalled());
    }

    public function test_save_object_identifiable(): void
    {
        $object = $this->thereIsAnObject()->identifiable();
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertCount(1, $this->contentOfTable($object->getTableName()));
    }

    public function test_save_unknown_object_identified_do_nothing(): void
    {
        $object = $this->thereIsAnObject()->identifiedBy($this->randomPositiveInt());
        $this->thereIsAnEmptyTableForObject($object);

        \DB::save($object);

        $this->assertCount(0, $this->contentOfTable($object->getTableName()));
    }

    public function test_save_unknown_object_identified_with_replace_flag_insert_new_row(): void
    {
        $object = $this->thereIsAnObject()->identifiedBy($this->randomPositiveInt());
        $this->thereIsAnEmptyTableForObject($object);

        $this->expectPhpError('');
        \DB::save($object, false, true);

        $this->assertCount(1, $this->contentOfTable($object->getTableName()));
    }

    public function test_save_object_identified_update_row(): void
    {
        $object = $this->thereIsAnObject()->identifiable();
        $this->thereIsAnEmptyTableForObject($object);
        \DB::save($object);
        $object->publicVar = 'update';

        \DB::save($object);

        $contentOfTable = $this->contentOfTable($object->getTableName());
        $this->assertCount(1, $contentOfTable);
        $this->assertSame('update', $contentOfTable[0]['publicVar']);
    }

    /**
     * @return ObjectMock
     */
    private function thereIsAnObject(): ObjectMock
    {
        return new ObjectMock();
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
        restore_error_handler();

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

    private function expectPhpError(string $message, int $errorType = E_USER_ERROR): void
    {
        $this->rollback();
        set_error_handler(function (int $errno, string $errstr) use ($message, $errorType): void {
            $this->assertSame($errorType, $errno, $errstr);
            $this->assertEquals($message, $errstr);
        });
    }

    private function thereIsAnEmptyTableForObject(ObjectMock $object): void
    {
        $this->thereIsATable($object->getTableName(), $object->getTableStructure());
    }

    private function randomPositiveInt(): int
    {
        return random_int(1, 2**30);
    }
}