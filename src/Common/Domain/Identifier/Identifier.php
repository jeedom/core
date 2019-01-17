<?php

namespace Jeedom\Common\Domain\Identifier;

interface Identifier
{
    /**
     * @param string $value
     *
     * @return Identifier
     */
    public static function fromString($value);

    /**
     * @return string
     */
    public function toString();

    /**
     * @param Identifier $id
     *
     * @return mixed
     */
    public function equals($id);
}
