<?php

namespace Tests\Unit\Mock\ObjectMock;

class DecryptableMock extends ObjectMock
{
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