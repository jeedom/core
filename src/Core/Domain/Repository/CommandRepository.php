<?php

namespace Jeedom\Core\Domain\Repository;

interface CommandRepository
{
    /**
     * @param \cmd $cmd
     *
     * @return CommandRepository
     */
    public function add(\cmd $cmd);

    /**
     * @param \cmd $cmd
     *
     * @return CommandRepository
     */
    public function refresh(\cmd $cmd);

    /**
     * @param $cmdId
     *
     * @return CommandRepository
     */
    public function remove($cmdId);

    /**
     * @param $id
     *
     * @return mixed
     */
    public function get($id);

    /**
     * @param $eqTypeName
     * @param $eqLogicName
     * @param $cmdName
     *
     * @return \cmd
     */
    public function findOneByTypeEqLogicNameCmdName($eqTypeName, $eqLogicName, $cmdName);

    /**
     * @param $eqLogicId
     * @param $cmd_name
     *
     * @return \cmd
     */
    public function findOneByEqLogicIdCmdName($eqLogicId, $cmd_name);

    /**
     * @param $objectName
     * @param $eqLogicName
     * @param $cmdName
     *
     * @return \cmd
     */
    public function findOneByObjectNameEqLogicNameCmdName($objectName, $eqLogicName, $cmdName);

    /**
     * @param $objectName
     * @param $cmdName
     *
     * @return \cmd
     */
    public function findOneByObjectNameCmdName($objectName, $cmdName);

    /**
     * @param $genericType
     * @param null $eqLogicId
     *
     * @return \cmd
     */
    public function findOneByGenericType($genericType, $eqLogicId = null);

    /**
     * @param $eqLogicId
     * @param $logicalId
     * @param null $type
     *
     * @return \cmd
     */
    public function findOneByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $type = null);

    /**
     * @param $eqLogicId
     * @param $genericType
     * @param null $type
     *
     * @return \cmd
     */
    public function findOneByEqLogicIdAndGenericType($eqLogicId, $genericType, $type = null);

    /**
     * @param $string
     *
     * @return \cmd
     */
    public function findOneByString($string);

    /**
     * @param array $ids
     *
     * @return \cmd[]
     */
    public function findByIds($ids);

    /**
     * @return \cmd[]
     */
    public function all();

    /**
     * @return \cmd[]
     */
    public function allHistoryCmd();

    /**
     * @param $eqLogicId
     * @param null $type
     * @param null $visible
     * @param null $eqLogic
     * @param null $hasGenericType
     *
     * @return \cmd[]
     */
    public function findByEqLogicId($eqLogicId, $type = null, $visible = null, $eqLogic = null, $hasGenericType = null);

    /**
     * @param $logical_id
     * @param null $type
     *
     * @return \cmd[]
     */
    public function findByLogicalId($logical_id, $type = null);

    /**
     * @param $generic_type
     * @param null $eqLogicId
     * @param bool $one
     *
     * @return \cmd[]
     */
    public function findByGenericType($generic_type, $eqLogicId = null);

    /**
     * @param $configuration
     * @param null $eqType
     *
     * @return \cmd[]
     */
    public function searchConfiguration($configuration, $eqType = null);

    /**
     * @param $eqLogicId
     * @param $configuration
     * @param null $type
     *
     * @return \cmd[]
     */
    public function searchConfigurationEqLogic($eqLogicId, $configuration, $type = null);

    /**
     * @param $template
     * @param null $eqType
     * @param null $type
     * @param null $subtype
     *
     * @return \cmd[]
     */
    public function searchTemplate($template, $eqType = null, $type = null, $subtype = null);

    /**
     * @param $eqLogicId
     * @param $logicalId
     * @param null $type
     *
     * @return \cmd[]
     */
    public function findByEqLogicIdAndLogicalId($eqLogicId, $logicalId, $type = null);

    /**
     * @param $eqLogicId
     * @param $genericType
     * @param null $type
     *
     * @return \cmd[]
     */
    public function findByEqLogicIdAndGenericType($eqLogicId, $genericType, $type = null);

    /**
     * @param $value
     * @param null $type
     * @param bool $onlyEnable
     *
     * @return \cmd[]
     */
    public function findByValue($value, $type = null, $onlyEnable = false);

    /**
     * @param $type
     * @param string $subType
     *
     * @return \cmd[]
     */
    public function findByTypeSubType($type, $subType = '');

    /**
     * @return string[]
     */
    public function listTypes();

    /**
     * @param $type
     *
     * @return string[]
     */
    public function listSubTypes($type);

    /**
     * @return string[]
     */
    public function listUnites();
}
