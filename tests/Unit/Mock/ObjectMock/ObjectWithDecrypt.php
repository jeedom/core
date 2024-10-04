<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithDecrypt extends ObjectMock
{
    public function decrypt(): void
    {
        $this->methodsCalled[] = 'decrypt';
    }
}