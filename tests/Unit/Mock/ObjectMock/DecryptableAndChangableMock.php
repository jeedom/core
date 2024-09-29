<?php

namespace Tests\Unit\Mock\ObjectMock;

class DecryptableAndChangableMock extends DecryptableMock
{
    private $changed = null;

    public function setChanged(bool $changed): void
    {
        $this->changed = $changed;
    }

    public function isChanged(): ?bool
    {
        return $this->changed;
    }
}