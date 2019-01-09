<?php

namespace Jeedom\Core\Plugin;

class Plugin
{
    public static function getEnable()
    {
        $sql = 'SELECT `value`,`plugin`
                FROM config
                WHERE `key`=\'active\'';
        $values = \DB::Prepare($sql, [], \DB::FETCH_TYPE_ALL);
        $return = [];

        foreach ($values as $value) {
            $return[$value['plugin']] = $value['value'];
        }

        return $return;
    }
}
