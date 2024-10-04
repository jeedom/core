<?php

namespace Tests\Unit\Mock\ObjectMock;

class ObjectWithSetId extends ObjectMock
{
    public function setId($id)
    {
    }

    public function badTypedId(): ObjectWithBadTypedId
    {
        return new ObjectWithBadTypedId();
    }
}