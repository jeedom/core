<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithIdField extends ObjectMock
{
    private $id = null;

    public function isIdSet(): bool
    {
        return $this->id !== null;
    }
}