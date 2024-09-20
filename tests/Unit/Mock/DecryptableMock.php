<?php

namespace Tests\Unit\Mock;

class DecryptableMock
{
    public $var;
    private $decrypted = false;

    public function decrypt(): void
    {
        $this->decrypted = true;
    }

    public function isDecrypted(): bool
    {
        return $this->decrypted;
    }
}