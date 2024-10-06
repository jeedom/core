<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectMockBuilder
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var bool
     */
    private $persist = false;

    /**
     * @var string|null
     */
    private $className;

    /**
     * @var string
     */
    private $classProperties;

    /**
     * @var string
     */
    private $classMethods;

    private string $tableStructure;

    public function __construct()
    {
        $this->tableName = str_replace('.', '_', uniqid('mock_', true));
        $this->classProperties = self::getBaseClassProperties();
        $this->classMethods = $this->getBaseClassMethods();
        $this->tableStructure = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, publicVar VARCHAR(255)';
    }

    public function changeable(): self
    {
        $this->addClassProperties(<<<PHP
    private \$changed = null;
PHP
        );

        $this->addClassMethods(<<<PHP
    public function setChanged(bool \$changed): void
    {
        \$this->methodsCalled[] = 'setChanged';
        \$this->changed = \$changed;
    }

    public function isChanged(): ?bool
    {
        return \$this->changed;
    }
PHP
        );

        return $this;
    }

    public function withHook(string $hook): self
    {
        $this->addClassMethods(<<<PHP
            public function {$hook}(): void
            {
                \$this->methodsCalled[] = '{$hook}';
            }

        PHP);

        return $this;
    }

    public function withField(string $field): self
    {
        if ('id' !== $field) {
            throw new \Exception('Unknown field '.$field);
        }

        $this->addClassProperties(<<<PHP
    private \$id = null;
PHP);

        $this->addClassMethods(<<<PHP
    public function isIdSet(): bool
    {
        return \$this->id !== null;
    }
PHP
        );

        return $this;
    }

    public function withoutHook(string $method): self
    {
        return $this;
    }

    public function withUniqueField(): self
    {
        $this->tableStructure = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, publicVar VARCHAR(255) NOT NULL, UNIQUE (publicVar)';

        $this->addClassMethods(<<<PHP
    public function __construct()
    {
        \$this->publicVar = 'test';
    }
PHP
        );

        return $this;
    }

    public function className(): string
    {
        return $this->buildClass();
    }

    public function withHooks(string ...$hooks): self
    {
        $object = $this;
        foreach ($hooks as $hook) {
            $object = $object->withHook($hook);
        }

        return $object;
    }

    public function identifiable(): self
    {
        $this->addClassProperties(<<<PHP
            private \$id;
        PHP);

        $this->addClassMethods(<<<PHP
            public function getId(): ?int
            {
                return \$this->id;
            }
            
            public function setId(?int \$id): void
            {
                \$this->id = \$id;
            }
        PHP);

        return $this;
    }

    private function addClassProperties(string $code): void
    {
        $this->classProperties .= $code;
    }

    private function addClassMethods(string $code): void
    {
        $this->classMethods .= $code;
    }

    public function identifiedBy(int $id): self
    {
        $this->identifiable();
        $this->id = $id;

        return $this;
    }

    public function persisted(): self
    {
        $this->persist = true;

        return $this;
    }

    public function object(): object
    {
        $className = $this->buildClass();
        $object = new $className($this->tableName);
        if (null !== $this->id) {
            if (!method_exists($object, 'setId')) {
                throw new \Exception('Can not set id.');
            }
            $object->setId($this->id);
        }

        $this->createObjectTable();
        if ($this->persist) {
            \DB::save($object);
        }

        $object->clearMethodsCalled();

        return $object;
    }

    private function createObjectTable(): void
    {
        $tableName = $this->tableName;
        $structure = $this->tableStructure;
        $connection = \DB::getConnection();
        $connection->exec('DROP TABLE IF EXISTS '.$tableName);
        $connection->exec('CREATE TABLE '.$tableName.' ('.$structure.')');
        if ('00000' !== $connection->errorCode()) {
            throw new \Exception($connection->errorInfo()[2]);
        }
    }

    private static function getBaseClassProperties(): string
    {
        return <<<PHP
            public \$publicVar = null;
        PHP;
    }

    private function getBaseClassMethods(): string
    {
        return <<<PHP

    public function getTableName(): string
    {
        return '{$this->tableName}';
    }
    
    public function __call(string \$method, array \$arguments)
    {
        \$this->methodsCalled[] = \$method;
    }

    public function isMethodCalled(string \$method): bool
    {
        return in_array(\$method, \$this->methodsCalled ?? [], true);
    }

    public function getMethodsCalled(): array
    {
        return \$this->methodsCalled;
    }
    
    public function clearMethodsCalled(): void
    {
        \$this->methodsCalled = [];
    }
PHP;
    }

    private function buildClass(): string
    {
        if (null !== $this->className) {
            return $this->className;
        }

        $newClassName = str_replace('.', '_', uniqid('ObjectMock_', true));
        $code = <<<PHP
        class {$newClassName}
        {
            {$this->classProperties}
            
            {$this->classMethods}
        };
        PHP;
        eval($code);

        $this->className = $newClassName;

        return $this->className;
    }
}
