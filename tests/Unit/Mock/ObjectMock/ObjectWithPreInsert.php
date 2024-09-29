<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithPreInsert extends ObjectMock
{
    private $preInsertCalled = false;

    public function preInsert(): void
    {
        $this->preInsertCalled = true;
    }

    public function isPreInsertCalled(): bool
    {
        return $this->preInsertCalled;
    }
}