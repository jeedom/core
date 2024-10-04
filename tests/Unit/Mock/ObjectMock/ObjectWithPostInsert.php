<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithPostInsert extends ObjectMock
{
    public function postInsert(): void
    {
        $this->methodsCalled[] = 'postInsert';
    }
}