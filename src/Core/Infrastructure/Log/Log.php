<?php

namespace Jeedom\Core\Infrastructure\Log;

class Log
{
    public static function getLevels() {
        $sql = 'SELECT `value`,`key`
                FROM config
                WHERE `key` LIKE \'log::level::%\'';
        $values = \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL);
        $return = array();
        foreach ($values as $value) {
            $return[$value['key']] = self::unserialize($value['value'], $value['value']);
        }

        return $return;
    }

    private static function unserialize($value, $default)
    {
        if ($default !== null) {
            if (!is_string($value)) {
                return $default;
            }
            $return = json_decode($value, true, 512, JSON_BIGINT_AS_STRING);
            if (!is_array($return)) {
                return $default;
            }
            return $return;
        }
        return ((is_string($value) && is_array(json_decode($value, true, 512, JSON_BIGINT_AS_STRING)))) ? true : false;
    }
}
