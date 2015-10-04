<?php

/**
 * Description of viewZone
 *
 * @author Gevrey Loïc
 */
/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class viewData {
	/*     * *************************Attributs****************************** */

	private $id;
	private $order;
	private $viewZone_id;
	private $type;
	private $link_id;
	private $configuration;

	/*     * ***********************Methode static*************************** */

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE id=:id
                ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byTypeLinkId($_type, $_link_id) {
		$value = array(
			'type' => $_type,
			'link_id' => $_link_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE type=:type
                    AND link_id=:link_id
                ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byviewZoneId($_viewZone_id) {
		$value = array(
			'viewZone_id' => $_viewZone_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewData
                WHERE viewZone_id=:viewZone_id
                ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function removeByTypeLinkId($_type, $_link_id) {
		$viewDatas = self::byTypeLinkId($_type, $_link_id);
		foreach ($viewDatas as $viewData) {
			$viewData->remove();
		}
		return true;
	}

	/*     * *********************Methode d'instance************************* */

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function getviewZone() {
		return viewZone::byId($this->getviewZone_id());
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		if (trim($id) == '') {
			$id = null;
		}
		$this->id = $id;
	}

	public function getviewZone_id() {
		return $this->viewZone_id;
	}

	public function setviewZone_id($viewZone_id) {
		$this->viewZone_id = $viewZone_id;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getLink_id() {
		return $this->link_id;
	}

	public function setLink_id($link_id) {
		$this->link_id = $link_id;
	}

	public function getLinkObject() {
		$type = $this->getType();
		if (class_exists($type)) {
			return $type::byId($this->getLink_id());
		}
		return false;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

}

?>
