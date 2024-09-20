<?php

namespace Tests\Unit\Mock;

class NotDecryptableWithChangedMock
{
    public $var;
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