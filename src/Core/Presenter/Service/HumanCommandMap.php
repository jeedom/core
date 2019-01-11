<?php

namespace Jeedom\Core\Presenter;

interface HumanCommandMap
{
    /**
     * @param $_input
     *
     * @return mixed
     */
    public function cmdToHumanReadable($_input);

    /**
     * @param $_input
     *
     * @param bool $isJson
     *
     * @return array|false|mixed|string
     */
    public function humanReadableToCmd($_input, $isJson = false);

    /**
     * @param $_input
     * @param bool $_quote
     *
     * @return array|mixed
     */
    public function cmdToValue($_input, $_quote = false);
}
