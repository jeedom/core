<?php

namespace Jeedom\Core\Infrastructure\Service;

use Jeedom\Core\Domain\Repository\CommandRepository;
use ReflectionException;

class RepositoryHumanCommandMap
{
    /**
     * @var CommandRepository
     */
    private $repository;

    public function __construct(CommandRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $_input
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function cmdToHumanReadable($_input) {
        if (is_object($_input)) {
            return $this->cmdToHumanReadableFromObject($_input);
        }

        if (is_array($_input)) {
            return $this->cmdToHumanReadableFromArray($_input);
        }

        return $this->cmdToHumanReadableFromString($_input);
    }

    /**
     * @param object $_input
     *
     * @return object
     * @throws ReflectionException
     */
    public function cmdToHumanReadableFromObject($_input)
    {
        return $this->setObjectValueByReflection($_input, [$this, 'cmdToHumanReadable']);
    }

    /**
     * @param $_input
     *
     * @param callable $valueTransformer
     *
     * @return mixed
     * @throws ReflectionException
     */
    private function setObjectValueByReflection($_input, callable $valueTransformer)
    {
        $reflections = [];
        $uuid = spl_object_hash($_input);
        if (!isset($reflections[$uuid])) {
            $reflections[$uuid] = new \ReflectionClass($_input);
        }
        $reflection = new \ReflectionClass($_input);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($_input);
            $property->setValue($_input, $valueTransformer($value));
            $property->setAccessible(false);
        }

        return $_input;
    }

    /**
     * @param array $_input
     *
     * @return array
     */
    public function cmdToHumanReadableFromArray($_input)
    {
        $json = json_encode($_input);
        $toHuman = $this->cmdToHumanReadableFromString($json);
        return json_decode($toHuman, true);
    }

    /**
     * @param string $_input
     *
     * @return string
     */
    public function cmdToHumanReadableFromString($_input)
    {
        $replace = array();
        preg_match_all("/#(\d*)#/", $_input, $matches);
        if (count($matches[1]) === 0) {
            return $_input;
        }

        $cmds = $this->repository->findByIds($matches[1]);
        foreach ($cmds as $cmd) {
            $hashId = '#' . $cmd->getId() . '#';
            if (!isset($replace[$hashId])) {
                $replace[$hashId] = '#' . $cmd->getHumanName() . '#';
            }
        }
        return str_replace(array_keys($replace), $replace, $_input);
    }

    /**
     * @param $_input
     *
     * @param bool $isJson
     *
     * @return array|false|mixed|string
     * @throws \ReflectionException
     */
    public function humanReadableToCmd($_input, $isJson = false) {
        if (is_json($_input)) {
            return $this->humanReadableToCmdFromArray(json_decode($_input, true), true); // array
        }

        if (is_array($_input)) {
            return $this->humanReadableToCmdFromArray($_input, $isJson);
        }

        if (is_object($_input)) {
            return $this->setObjectValueByReflection($_input, [self::class, 'humanReadableToCmd']);
        }

        return $this->humanReadableToCmdFromString($_input);
    }

    /**
     * @param array $_input
     * @param bool $isJson
     *
     * @return array|string
     * @throws \ReflectionException
     */
    public function humanReadableToCmdFromArray($_input, $isJson = false)
    {
        foreach ($_input as $key => $value) {
            $_input[$key] = $this->humanReadableToCmd($value);
        }

        return $isJson ? json_encode($_input, JSON_UNESCAPED_UNICODE) : $_input;
    }

    /**
     * @param string $_input
     *
     * @return string
     */
    public function humanReadableToCmdFromString($_input)
    {
        $replace = array();
        preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $_input, $matches);
        if (count($matches) !== 4) {
            return $_input;
        }

        $countMatches = count($matches[0]);
        for ($i = 0; $i < $countMatches; $i++) {
            if (isset($replace[$matches[0][$i]]) || !isset($matches[1][$i], $matches[2][$i], $matches[3][$i])) {
                continue;
            }

            $cmd = $this->repository->findOneByObjectNameEqLogicNameCmdName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
            if (is_object($cmd)) {
                $replace[$matches[0][$i]] = '#' . $cmd->getId() . '#';
            }
        }

        return str_replace(array_keys($replace), $replace, $_input);
    }


    /**
     * @param $_input
     * @param bool $_quote
     *
     * @return array|mixed
     * @throws ReflectionException
     */
    public function cmdToValue($_input, $_quote = false) {
        if (is_object($_input)) {
            return $this->setObjectValueByReflection($_input, [self::class, 'cmdToValue']);
        }
        if (is_array($_input)) {
            foreach ($_input as $key => $value) {
                $_input[$key] = $this->cmdToValue($value, $_quote);
            }
            return $_input;
        }
        $json = is_json($_input);
        $replace = array();
        preg_match_all('/#(\d*)#/', $_input, $matches);
        foreach ($matches[1] as $cmd_id) {
            $hashId = '#' . $cmd_id . '#';
            if (isset($replace[$hashId])) {
                continue;
            }
            $cache = \cache::byKey('cmdCacheAttr' . $cmd_id)->getValue();
            if (\utils::getJsonAttr($cache, 'value', null) !== null) {
                $collectDate = \utils::getJsonAttr($cache, 'collectDate', date('Y-m-d H:i:s'));
                $valueDate = \utils::getJsonAttr($cache, 'valueDate', date('Y-m-d H:i:s'));
                $cmd_value = \utils::getJsonAttr($cache, 'value', '');
            } else {
                $cmd = $this->repository->get($cmd_id);
                if (!is_object($cmd) || $cmd->getType() != 'info') {
                    continue;
                }
                $cmd_value = $cmd->execCmd(null, true, $_quote);
                $collectDate = $cmd->getCollectDate();
                $valueDate = $cmd->getValueDate();
            }
            if ($_quote && (strpos($cmd_value, ' ') !== false || preg_match("/[a-zA-Z#]/", $cmd_value) || $cmd_value === '')) {
                $cmd_value = '"' . trim($cmd_value, '"') . '"';
            }
            if (!$json) {
                $replace['"#' . $cmd_id . '#"'] = $cmd_value;
                $replace[$hashId] = $cmd_value;
                $replace['#collectDate' . $cmd_id . '#'] = $collectDate;
                $replace['#valueDate' . $cmd_id . '#'] = $valueDate;
            } else {
                $replace[$hashId] = trim(json_encode($cmd_value), '"');
                $replace['#valueDate' . $cmd_id . '#'] = trim(json_encode($valueDate), '"');
                $replace['#collectDate' . $cmd_id . '#'] = trim(json_encode($collectDate), '"');
            }
        }
        return str_replace(array_keys($replace), $replace, $_input);
    }
}
