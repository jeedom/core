<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectMockBuilder
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var int
     */
    private $persistCount = 0;

    /**
     * @var string|null
     */
    private $className;

    /**
     * @var string
     */
    private $classMethods;

    private $tableStructure;
    private $fields = [
        'publicVar' => null,
    ];

    private $publicFields = [
        'publicVar',
    ];

    private $uniqueFields = [];

    public function __construct(?object $baseObject = null)
    {
        if (null === $baseObject) {
            $this->tableName = str_replace('.', '_', uniqid('mock_', true));
            $this->classMethods = $this->getBaseClassMethods();
            $this->tableStructure = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
        } else {
            $this->tableName = $baseObject->getTableName();
            $this->className = get_class($baseObject);
        }
    }

    public function changeable(): self
    {
        $this->addClassMethods(<<<PHP
    public function setChanged(bool \$changed): void
    {
        \$this->methodsCalled[] = 'setChanged';
        \$this->changed = \$changed;
    }

    public function getChanged(): ?bool
    {
        \$this->methodsCalled[] = 'getChanged';
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

    public function withField(string $field, $value = null): self
    {
        $this->fields[$field] = $value;

        if ('id' !== $field) {
            return $this;
        }

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

    public function withUniqueField(string $field = 'publicVar', $value = 'test'): self
    {
        $this->fields[$field] = $value;
        $this->uniqueFields[] = 'publicVar';

        $codeValue = var_export($value, true);
        $this->addClassMethods(<<<PHP
    public function __construct()
    {
        \$this->{$field} = {$codeValue};
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
        $this->fields['id'] = null;

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

    public function persisted(int $count = 1): self
    {
        $this->persistCount = $count;

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
        foreach ($this->fields as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            $object->{$key} = $value;
        }

        $this->createObjectTable();
        while ($this->persistCount > 1) {
            $persistedObject = clone $object;
            \DB::save($persistedObject);
            --$this->persistCount;
        }

        if ($this->persistCount > 0) {
            \DB::save($object);
        }
        $object->clearMethodsCalled();

        return $object;
    }

    private function createObjectTable(): void
    {
        $tableName = $this->tableName;
        $structure = $this->tableStructure;
        foreach ($this->fields as $key => $value) {
            if ($key === 'id') {
                continue;
            }
            $structure .= ', '.$key.' VARCHAR(255) DEFAULT NULL';
        }

        foreach ($this->uniqueFields as $key) {
            $structure .= ', UNIQUE ('.$key.')';
        }
        $connection = \DB::getConnection();
        $connection->exec('DROP TABLE IF EXISTS '.$tableName);
        $connection->exec('CREATE TABLE '.$tableName.' ('.$structure.')');
        if ('00000' !== $connection->errorCode()) {
            throw new \Exception($connection->errorInfo()[2]);
        }
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
        $classProperties = '';
        foreach ($this->fields as $parameter => $value) {
            $visibility = 'private';
            if (in_array($parameter, $this->publicFields, true)) {
                $visibility = 'public';
            }
            $classProperties .= <<<PHP
                {$visibility} \${$parameter} = null;

            PHP;
        }
        $code = <<<PHP
        class {$newClassName}
        {
            {$classProperties}
            
            {$this->classMethods}
        }
        PHP;
        eval($code);

        $this->className = $newClassName;

        return $this->className;
    }
}
