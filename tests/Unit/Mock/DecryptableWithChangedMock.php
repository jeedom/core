<?php

namespace Tests\Unit\Mock;

class DecryptableWithChangedMock extends DecryptableMock
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