<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectMock
{
    public $publicVar = null;

    public function __construct()
    {
        $this->methodsCalled = []; // do not declare as property
    }

    public function getTableName(): string
    {
        return 'object_mock';
    }

    public function getTableStructure(): string
    {
        return 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, publicVar VARCHAR(255)';
    }

    public function withMethod(string $method): self
    {
        switch ($method) {
            case 'setId':
                return new ObjectWithSetId();
            case 'preSave':
                return new ObjectWithPreSave();
            case 'preInsert':
                return new ObjectWithPreInsert();
            case 'postInsert':
                return new ObjectWithPostInsert();
            case 'encrypt':
                return new ObjectWithEncrypt();
            case 'decrypt':
                return new ObjectWithDecrypt();
            case 'postSave':
                return new ObjectWithPostSave();

        }

        throw new \Exception('Unknown method ' . $method);
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
}