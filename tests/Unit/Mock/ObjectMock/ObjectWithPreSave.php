<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithPreSave extends ObjectMock
{
    private $preSaveCalled = false;

    public function preSave(): void
    {
        $this->preSaveCalled = true;
    }

    public function isPreSaveCalled(): bool
    {
        return $this->preSaveCalled;
    }
}