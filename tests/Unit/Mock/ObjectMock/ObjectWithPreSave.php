<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithPreSave extends ObjectMock
{
    public function preSave(): void
    {
        $this->methodsCalled[] = 'preSave';
    }
}