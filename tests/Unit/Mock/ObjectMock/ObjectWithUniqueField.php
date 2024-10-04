<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithUniqueField extends ObjectMock
{
    public function __construct()
    {
        parent::__construct();
        $this->publicVar = 'test';
    }

    public function getTableStructure(): string
    {
        return 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, publicVar VARCHAR(255) NOT NULL, UNIQUE (publicVar)';
    }
}