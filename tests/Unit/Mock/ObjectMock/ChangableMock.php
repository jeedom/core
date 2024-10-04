<?php

namespace Tests\Unit\Mock\ObjectMock;

class ChangableMock extends ObjectMock
{
    private $changed = null;

    public function setChanged(bool $changed): void
    {
        $this->methodsCalled[] = 'setChanged';
        $this->changed = $changed;
    }

    public function isChanged(): ?bool
    {
        return $this->changed;
    }
}