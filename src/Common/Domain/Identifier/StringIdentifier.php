<?php

namespace Jeedom\Common\Domain\Identifier;

abstract class StringIdentifier implements Identifier
{
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = (string) $value;
    }

    /**
     * @param $value
     *
     * @return StringIdentifier
     */
    public static function fromString($value)
    {
        return new static($value);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }

    /**
     * @param StringIdentifier $id
     *
     * @return bool
     */
    public function equals($id)
    {
        return ($id instanceof self) && $this->value === $id->value;
    }
}
