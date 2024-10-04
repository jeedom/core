<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithPostSave extends ObjectMock
{
    public function postSave(): void
    {
        $this->methodsCalled[] = 'postSave';
    }
}