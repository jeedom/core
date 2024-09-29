<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectMock
{
    public $publicVar = null;

    public function getTableName(): string
    {
        return 'object_mock';
    }

    public function getTableStructure(): string
    {
        return 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, publicVar VARCHAR(255)';
    }
}