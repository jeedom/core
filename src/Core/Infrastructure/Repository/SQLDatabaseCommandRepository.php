<?php

namespace Jeedom\Core\Infrastructure\Repository;

use Jeedom\Core\Domain\Repository\CommandRepository;

class SQLDatabaseCommandRepository implements CommandRepository
{
    private $fields;

    /**
     * {@inheritdoc}
     */
    public function add(\cmd $cmd)
    {
        if ($cmd->getName() == '') {
            throw new \DomainException(__('Le nom de la commande ne peut pas être vide :', __FILE__) . print_r($cmd, true));
        }
        if ($cmd->getType() == '') {
            throw new \DomainException($cmd->getHumanName() . ' ' . __('Le type de la commande ne peut pas être vide :', __FILE__) . print_r($cmd, true));
        }
        if ($cmd->getSubType() == '') {
            throw new \DomainException($cmd->getHumanName() . ' ' . __('Le sous-type de la commande ne peut pas être vide :', __FILE__) . print_r($cmd, true));
        }
        if ($cmd->getEqLogic_id() == '') {
            throw new \DomainException($cmd->getHumanName() . ' ' . __('Vous ne pouvez pas créer une commande sans la rattacher à un équipement', __FILE__));
        }
        if ($cmd->getConfiguration('maxValue') != '' && $cmd->getConfiguration('minValue') != '' && $cmd->getConfiguration('minValue') > $cmd->getConfiguration('maxValue')) {
            throw new \DomainException($cmd->getHumanName() . ' ' . __('La valeur minimum de la commande ne peut etre supérieure à la valeur maximum', __FILE__));
        }
        if ($cmd->getEqType() == '') {
            $cmd->setEqType($cmd->getEqLogic()->getEqType_name());
        }
        if ($cmd->getDisplay('generic_type') !== '' && $cmd->getGeneric_type() == '') {
            $cmd->setGeneric_type($cmd->getDisplay('generic_type'));
            $cmd->setDisplay('generic_type', '');
        }
        \DB::save($cmd);
        if ($cmd->needRefreshWidget()) {
            $cmd->refreshWidget(false);
            $cmd->getEqLogic()->refreshWidget();
        }
        if ($cmd->needRefreshAlert() && $cmd->getType() == 'info') {
            $value = $cmd->execCmd();
            $level = $cmd->checkAlertLevel($value);
            if ($level != $cmd->getCache('alertLevel')) {
                $cmd->actionAlertLevel($level, $value);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function refresh(\cmd $cmd)
    {
        \DB::refresh($cmd);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function remove($cmdId)
    {
        \viewData::removeByTypeLinkId('cmd', $cmdId);
        \dataStore::removeByTypeLinkId('cmd', $cmdId);
        $cmd = $this->get($cmdId);
        $cmd->getEqLogic()->emptyCacheWidget();
        $cmd->emptyHistory();
        \cache::delete('cmdCacheAttr' . $cmdId);
        \cache::delete('cmd' . $cmdId);
        $date = new \DateTimeImmutable('now');
        \jeedom::addRemoveHistory(
            ['id' => $cmdId, 'name' => $cmd->getHumanName(), 'date' => $date->format('Y-m-d H:i:s'), 'type' => 'cmd']
        );
        \DB::remove($cmd);

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function get($_id)
    {
        if ($_id == '') {
            return null;
        }
        $values = [
            'id' => $_id,
        ];
        $sql = 'SELECT ' . $this->getFields() . '
		FROM cmd
		WHERE id=:id';

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByIds($_ids)
    {
        if (!is_array($_ids) || count($_ids) === 0) {
            return [];
        }

        $in = trim(implode(',', $_ids), ',');
        if (empty($in)) {
            return [];
        }

        $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE id IN (' . $in . ')';

        return $this->getResults($sql);

    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function all()
    {
        $sql = 'SELECT ' . $this->getFields() . ' FROM cmd ORDER BY id';

        return $this->getResults($sql);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function allHistoryCmd()
    {
        $sql = 'SELECT ' . $this->getFields('c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE isHistorized=1
		AND type=\'info\'
		ORDER BY ob.position, ob.name, el.name, c.name';
        $result1 = $this->getResults($sql);

        $sql = 'SELECT ' . $this->getFields('c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE el.object_id IS NULL
		AND isHistorized=1
		AND type=\'info\'
		ORDER BY el.name, c.name';
        $result2 = $this->getResults($sql);

        return array_merge($result1, $result2);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null, $_has_generic_type = null)
    {
        $values = [];
        if (is_array($_eqLogic_id)) {
            $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE eqLogic_id IN (' . implode(',', $_eqLogic_id) . ')';
        } else {
            $values = [
                'eqLogic_id' => $_eqLogic_id,
            ];
            $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE eqLogic_id=:eqLogic_id';
        }
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        if ($_visible !== null) {
            $sql .= ' AND `isVisible`=1';
        }
        if ($_has_generic_type) {
            $sql .= ' AND `generic_type` IS NOT NULL';
        }
        $sql .= ' ORDER BY `order`,`name`';

        return $this->getResults($sql, $values, $_eqLogic);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByLogicalId($_logical_id, $_type = null)
    {
        $values = [
            'logicalId' => $_logical_id,
        ];
        $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE logicalId=:logicalId';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND `type`=:type';
        }
        $sql .= ' ORDER BY `order`';

        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByGenericType($_generic_type, $_eqLogic_id = null)
    {
        return $this->findByGenericType($_generic_type, $_eqLogic_id, true);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByGenericType($_generic_type, $_eqLogic_id = null, $one = false)
    {
        $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE ';
        $values = [];
        if (is_array($_generic_type)) {
            $in = implode(',', array_map(function($value) { return "'".$value."'"; }, $_generic_type));
            $sql .= 'generic_type IN (' . $in . ')';
        } else {
            $values = [
                'generic_type' => $_generic_type,
            ];
            $sql .= 'generic_type=:generic_type';
        }
        if ($_eqLogic_id !== null) {
            $values['eqLogic_id'] = $_eqLogic_id;
            $sql .= ' AND `eqLogic_id`=:eqLogic_id';
        }
        $sql .= ' ORDER BY `order`';

        if ($one) {
            return $this->getOneResult($sql, $values);
        }

        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function searchConfiguration($_configuration, $_eqType = null)
    {
        $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE ';
        if (!is_array($_configuration)) {
            $sql .= 'configuration LIKE :configuration';
            $values = [
                'configuration' => '%' . $_configuration . '%',
            ];
        } else {
            $i = 0;
            $conditions = [];
            $values = [];
            foreach ($_configuration as $config) {
                $values['configuration' . $i] = '%' . $config . '%';
                $conditions[] = 'configuration LIKE :configuration' . $i;
                $i++;
            }
            $sql .= implode(' OR ', $conditions);
        }
        if ($_eqType !== null) {
            $values['eqType'] = $_eqType;
            $sql .= ' AND eqType=:eqType ';
        }
        $sql .= ' ORDER BY name';

        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type = null)
    {
        $values = [
            'configuration' => '%' . $_configuration . '%',
            'eqLogic_id' => $_eqLogic_id,
        ];
        $sql = 'SELECT ' . $this->getFields() . ' FROM cmd WHERE eqLogic_id=:eqLogic_id';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type ';
        }
        $sql .= ' AND configuration LIKE :configuration';

        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function searchTemplate($_template, $_eqType = null, $_type = null, $_subtype = null)
    {
        $values = [
            'template' => '%' . $_template . '%',
        ];
        $sql = 'SELECT ' . $this->getFields() . '
		FROM cmd
		WHERE template LIKE :template';
        if ($_eqType !== null) {
            $values['eqType'] = $_eqType;
            $sql .= ' AND eqType=:eqType ';
        }
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type ';
        }
        if ($_subtype !== null) {
            $values['subType'] = $_subtype;
            $sql .= ' AND subType=:subType ';
        }
        $sql .= ' ORDER BY name';

        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type = null)
    {
        return $this->findByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type, false);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_type = null, $_multiple = true)
    {
        $values = [
            'eqLogic_id' => $_eqLogic_id,
            'logicalId' => $_logicalId,
        ];
        $sql = 'SELECT ' . $this->getFields() . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND logicalId=:logicalId';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type';
        }
        if ($_multiple) {
            return $this->getResults($sql, $values);
        }

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type = null)
    {
        return $this->findByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type, false);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_type = null, $_multiple = true)
    {
        $values = [
            'eqLogic_id' => $_eqLogic_id,
            'generic_type' => $_generic_type,
        ];
        $sql = 'SELECT ' . $this->getFields() . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND generic_type=:generic_type';
        if ($_type !== null) {
            $values['type'] = $_type;
            $sql .= ' AND type=:type';
        }
        if ($_multiple) {
            return $this->getResults($sql, $values);
        }

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByValue($_value, $_type = null, $_onlyEnable = false)
    {
        $values = [
            'value' => $_value,
            'search' => '%#' . $_value . '#%',
        ];

        if ($_onlyEnable) {
            $sql = 'SELECT ' . $this->getFields('c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE ( value=:value OR value LIKE :search)
			AND el.isEnable=1
			AND c.id!=:value';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND c.type=:type ';
            }
        } else {
            $sql = 'SELECT ' . $this->getFields() . '
			FROM cmd
			WHERE ( value=:value OR value LIKE :search)
			AND id!=:value';
            if ($_type !== null) {
                $values['type'] = $_type;
                $sql .= ' AND type=:type ';
            }
        }

        return $this->getResults($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name)
    {
        $values = [
            'eqType_name' => $_eqType_name,
            'eqLogic_name' => $_eqLogic_name,
            'cmd_name' => $_cmd_name,
        ];
        $sql = 'SELECT ' . $this->getFields('c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE c.name=:cmd_name
		AND el.name=:eqLogic_name
		AND el.eqType_name=:eqType_name';

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByEqLogicIdCmdName($_eqLogic_id, $_cmd_name)
    {
        $values = [
            'eqLogic_id' => $_eqLogic_id,
            'cmd_name' => $_cmd_name,
        ];
        $sql = 'SELECT ' . $this->getFields('c') . '
		FROM cmd c
		WHERE c.name=:cmd_name
		AND c.eqLogic_id=:eqLogic_id';
        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name)
    {
        $values = [
            'eqLogic_name' => $_eqLogic_name,
            'cmd_name' => (html_entity_decode($_cmd_name) != '') ? html_entity_decode($_cmd_name) : $_cmd_name,
        ];

        if ($_object_name == __('Aucun', __FILE__)) {
            $sql = 'SELECT ' . $this->getFields('c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND el.object_id IS NULL';
        } else {
            $values['object_name'] = $_object_name;
            $sql = 'SELECT ' . $this->getFields('c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			INNER JOIN object ob ON el.object_id=ob.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND ob.name=:object_name';
        }

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findOneByObjectNameCmdName($_object_name, $_cmd_name)
    {
        $values = [
            'object_name' => $_object_name,
            'cmd_name' => $_cmd_name,
        ];
        $sql = 'SELECT ' . $this->getFields('c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE c.name=:cmd_name
		AND ob.name=:object_name';

        return $this->getOneResult($sql, $values);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function findByTypeSubType($_type, $_subType = '')
    {
        $values = [
            'type' => $_type,
        ];
        $sql = 'SELECT ' . $this->getFields('c') . '
		FROM cmd c
		WHERE c.type=:type';
        if ($_subType != '') {
            $values['subtype'] = $_subType;
            $sql .= ' AND c.subtype=:subtype';
        }

        return $this->getResults($sql, $values);
    }

    /**
     * @param $_inputs
     * @param null $_eqLogic
     *
     * @return \cmd[]|\cmd
     */
    private static function cast($_inputs, $_eqLogic = null)
    {

        if ($_inputs instanceof \cmd && class_exists($_inputs->getEqType() . 'Cmd')) {
            $destination = $_inputs->getEqType() . 'Cmd';
            if ($_eqLogic !== null) {
                $_inputs->setEqLogic($_eqLogic);
            }
            $obj_in = serialize($_inputs);
            return unserialize('O:' . strlen($destination) . ':"' . $destination . '":' . substr($obj_in, $obj_in[2] + 7));
        }
        if (is_array($_inputs)) {
            $return = [];
            foreach ($_inputs as $input) {
                if ($_eqLogic !== null) {
                    $input->_eqLogic = $_eqLogic;
                }
                $return[] = self::cast($input);
            }
            return $return;
        }
        return $_inputs;
    }

    /**
     * @param string $alias
     *
     * @return string
     */
    private function getFields($alias = '')
    {
        if (null === $this->fields[$alias]) {
            $this->fields[$alias] = \DB::buildField(\cmd::class, $alias);
        }

        return $this->fields[$alias];
    }

    /**
     * @param $sql
     * @param array $values
     *
     * @param null $eqLogic
     *
     * @return \cmd[]
     * @throws \Exception
     */
    private function getResults($sql, $values = [], $eqLogic = null)
    {
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL, \PDO::FETCH_CLASS, __CLASS__), $eqLogic);
    }

    /**
     * @param $sql
     * @param array $values
     *
     * @param null $eqLogic
     *
     * @return \cmd
     * @throws \Exception
     */
    private function getOneResult($sql, $values = [], $eqLogic = null)
    {
        return self::cast(\DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, \PDO::FETCH_CLASS, __CLASS__), $eqLogic);
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    public function listTypes()
    {
        $sql = 'SELECT DISTINCT(type) as type FROM cmd';

        return \DB::Prepare($sql, array(), \DB::FETCH_TYPE_ALL);
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    public function listSubTypes($type)
    {
        $values = [];
        $sql = 'SELECT distinct(subType) as subtype';
        if ($type != '') {
            $values['type'] = $type;
            $sql .= ' WHERE type=:type';
        }
        $sql .= ' FROM cmd';
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL);
    }

    /**
     * @return string[]
     * @throws \Exception
     */
    public function listUnites()
    {
        $sql = 'SELECT distinct(unite) as unite FROM cmd';

        return \DB::Prepare($sql, [], \DB::FETCH_TYPE_ALL);
    }
}
