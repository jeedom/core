<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectMock
{
    public $publicVar = null;

    public function __construct()
    {
        $this->methodsCalled = []; // do not declare as property
        $this->tableName = str_replace('.', '_', uniqid('mock_', true));
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getTableStructure(): string
    {
        return 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, publicVar VARCHAR(255)';
    }

    public function changeable(): ChangableMock
    {
        return new ChangableMock();
    }

    public function withHook(string $hook): self
    {
        $parentClassName = static::class;
        $newClassName = str_replace('\\', '_', $parentClassName).'__H'.$hook;
        $code = <<<PHP
        if (class_exists('{$newClassName}')) {
            return;
        }
        
        class {$newClassName} extends {$parentClassName} 
        {
            public function {$hook}(): void
            {
                \$this->methodsCalled[] = '{$hook}';
            }
        };
        PHP;
        eval($code);

        return new $newClassName();
    }

    public function __call(string $method, array $arguments)
    {
        $this->methodsCalled[] = $method;
    }

    public function isMethodCalled(string $method): bool
    {
        return in_array($method, $this->methodsCalled ?? [], true);
    }

    public function withField(string $field): self
    {
        if ($field === 'id') {
            return new ObjectWithIdField();
        }

        throw new \Exception('Unknown field ' . $field);
    }

    public function withoutMethod(string $method): self
    {
        return $this;
    }

    public function withUniqueField(): self
    {
        return new ObjectWithUniqueField();
    }

    public function className(): string
    {
        return static::class;
    }

    public function withHooks(string ...$hooks): self
    {
        $object = $this;
        foreach ($hooks as $hook) {
            $object = $object->withHook($hook);
        }

        return $object;
    }

    public function getMethodsCalled(): array
    {
        return $this->methodsCalled;
    }

    public function identifiable(): self
    {
        $parentClassName = static::class;
        $newClassName = str_replace('\\', '_', $parentClassName).'__ID';
        $code = <<<PHP
        if (class_exists('{$newClassName}')) {
            return;
        }
        
        class {$newClassName} extends {$parentClassName} 
        {
            private \$id;
            
            public function getId(): ?int
            {
                return \$this->id;
            }
            
            public function setId(?int \$id): void
            {
                \$this->id = \$id;
            }
        };
        PHP;
        eval($code);

        return new $newClassName();
    }

    public function identifiedBy(int $id): self
    {
        $object = $this->identifiable();
        $object->setId($id);

        return $object;
    }
}