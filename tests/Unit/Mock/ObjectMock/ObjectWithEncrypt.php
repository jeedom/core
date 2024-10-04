<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithEncrypt extends ObjectMock
{
    public function encrypt(): void
    {
        $this->methodsCalled[] = 'encrypt';
    }

}