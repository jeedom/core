<?php

namespace Jeedom\Core\Configuration;

class SQLDatabaseConfiguration implements Configuration
{
    private $plugin;

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $values = [
            'plugin' => $this->plugin,
            'key' => $key,
        ];
        $sql = 'SELECT `value` FROM config WHERE `key`=:key AND plugin=:plugin';
        $value = \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW);

        return self::unserialize($value['value'], $value['value']);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $exists = null !== $this->get($key);

        $values = [
            'plugin' => $this->plugin,
            'key' => $key,
            'value' => self::serialize($value),
        ];
        $sql = !$exists
            ? 'INSERT INTO config (key, value, plugin) VALUES (:key, :value, :plugin)'
            : 'UPDATE config SET `value`=:value WHERE `key`=:key AND plugin=:plugin'
        ;
        \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $values = [
            'plugin' => $this->plugin,
        ];
        $sql = 'DELETE FROM config WHERE plugin=:plugin';

        if ($key !== '*' || $this->plugin === 'core') {
            $values['key'] = $key;
            $sql .= ' AND `key`=:key';
        }

        \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function multiGet(array $keys, $default = null)
    {
        if (!is_array($keys) || \count($keys) === 0) {
            return [];
        }

        $values = [
            'plugin' => $this->plugin,
        ];
        $stringKeys = '(\'' . implode('\',\'', $keys) . '\')';
        $sql = 'SELECT `key`,`value` FROM config WHERE `key` IN ' . $stringKeys . ' AND plugin=:plugin';
        $values = \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL);

        if (!is_array($default)) {
            $default = array_fill_keys($keys, $default);
        } else {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $default)) {
                    $default[$key] = null;
                }
            }
        }
        $return = array_intersect_key($default, array_flip($keys));

        foreach ($values as $value) {
            $return[$value['key']] = self::unserialize($value['value'], $value['value']);
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function search($pattern)
    {
        $values = [
            'plugin' => $this->plugin,
            'key' => '%' . $pattern . '%',
        ];
        $sql = 'SELECT * FROM config WHERE `key` LIKE :key AND plugin=:plugin';
        $results = \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL);

        foreach ($results as &$result) {
            $result['value'] = self::unserialize($result['value'], $result['value']);
        }

        return $results;
    }

    private static function unserialize($value, $default)
    {
        if (null === $value) {
            return null;
        }
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

    private static function serialize($value)
    {
        return is_object($value) || is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }
}
