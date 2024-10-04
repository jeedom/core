<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithPreInsert extends ObjectMock
{
    public function preInsert(): void
    {
        $this->methodsCalled[] = 'preInsert';
    }
}