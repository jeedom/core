<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class user {
	/*     * *************************Attributs****************************** */

	private $id;
	private $login;
	private $password;
	private $options;
	private $rights;
	private $enable = 1;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user
        WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Retourne un object utilisateur (si les information de connection sont valide)
	 * @param string $_login nom d'utilisateur
	 * @param string $_mdp motsz de passe en sha1
	 * @return user object user
	 */
	public static function connect($_login, $_mdp, $_passAlreadyEncode = false) {
		if ($_passAlreadyEncode) {
			$sMdp = $_mdp;
		} else {
			$sMdp = sha1($_mdp);
		}
		if (config::byKey('ldap:enable') == '1') {
			log::add("connection", "debug", __('Authentification par LDAP', __FILE__));
			$ad = self::connectToLDAP();
			if ($ad !== false) {
				log::add("connection", "debug", __('Connection au LDAP OK', __FILE__));
				$ad = ldap_connect(config::byKey('ldap:host'), config::byKey('ldap:port'));
				ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
				if (!ldap_bind($ad, 'uid=' . $_login . ',' . config::byKey('ldap:basedn'), $_mdp)) {
					log::add("connection", "info", __('Mot de passe erroné (', __FILE__) . $_login . ')');
					return false;
				}
				log::add("connection", "debug", __('Bind user OK', __FILE__));
				$result = ldap_search($ad, 'uid=' . $_login . ',' . config::byKey('ldap:basedn'), config::byKey('ldap:filter'));
				log::add("connection", "info", __('Recherche LDAP (', __FILE__) . $_login . ')');
				if ($result) {
					$entries = ldap_get_entries($ad, $result);
					if ($entries['count'] > 0) {
						$user = self::byLogin($_login);
						if (is_object($user)) {
							$user->setPassword($sMdp);
							$user->setOptions('lastConnection', date('Y-m-d H:i:s'));
							$user->save();
							return $user;
						}
						$user = new user;
						$user->setLogin($_login);
						$user->setPassword($sMdp);
						$user->setOptions('lastConnection', date('Y-m-d H:i:s'));
						$user->save();
						log::add("connection", "info", __('Utilisateur créé depuis le LDAP : ', __FILE__) . $_login);
						jeedom::event('user_connect');
						return $user;
					} else {
						$user = self::byLogin($_login);
						if (is_object($user)) {
							$user->remove();
						}
						log::add("connection", "info", __('Utilisateur non autorisé à accéder à Jeedom (', __FILE__) . $_login . ')');
						return false;
					}
				} else {
					$user = self::byLogin($_login);
					if (is_object($user)) {
						$user->remove();
					}
					log::add("connection", "info", __('Utilisateur non autorisé à accéder à Jeedom (', __FILE__) . $_login . ')');
					return false;
				}
				return false;
			} else {
				log::add("connection", "info", __('Impossible de se connecter au LDAP', __FILE__));
			}
		}
		$values = array(
			'login' => $_login,
			'password' => $sMdp,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user
        WHERE login=:login
        AND password=:password';
		$user = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		if (is_object($user)) {
			$user->setOptions('lastConnection', date('Y-m-d H:i:s'));
			$user->save();
			jeedom::event('user_connect');
			if ($user->getOptions('validity_limit') != '' && strtotime('now') > strtotime($user->getOptions('validity_limit'))) {
				$user->remove();
				return false;
			}
		}
		return $user;
	}

	public static function cleanOutdatedUser() {
		foreach (user::all() as $user) {
			if ($user->getOptions('validity_limit') != '' && strtotime('now') > strtotime($user->getOptions('validity_limit'))) {
				$user->remove();
			}
		}
	}

	public static function connectToLDAP() {
		$ad = ldap_connect(config::byKey('ldap:host'), config::byKey('ldap:port'));
		ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
		if (ldap_bind($ad, config::byKey('ldap:username'), config::byKey('ldap:password'))) {
			return $ad;
		}
		return false;
	}

	public static function byLogin($_login) {
		$values = array(
			'login' => $_login,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user
        WHERE login=:login';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byKey($_key) {
		$values = array(
			'key' => '%' . $_key . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user
        WHERE options LIKE :key';
		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		if (is_object($result)) {

			if ($result->getOptions('registerDevice') == $_key || $result->getOptions('registerDesktop') == $_key) {
				return $result;
			}
		}
		return null;
	}

	/**
	 *
	 * @return array de tous les utilisateurs
	 */
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function searchByRight($_rights) {
		$values = array(
			'rights' => '%"' . $_rights . '":1%',
			'rights2' => '%"' . $_rights . '":"1"%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user
        WHERE rights LIKE :rights
        OR rights LIKE :rights2';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function createTemporary($_hours) {
		$user = new self();
		$user->setLogin('temp_' . config::genKey());
		$user->setPassword(config::genKey(64));
		$user->setRights('admin', 1);
		$user->setOptions('validity_limit', date('Y-m-d H:i:s', strtotime('+' . $_hours . ' hour now')));
		$user->save();
		return $user;
	}

	public static function hasDefaultIdentification() {
		$sql = 'SELECT count(id) as nb
        FROM user
        WHERE login="admin"
        AND password=SHA1("admin")';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		return $result['nb'];
	}

	/*     * *********************Méthodes d'instance************************* */

	public function presave() {
		if ($this->getLogin() == '') {
			throw new Exception(__('Le nom d\'utilisateur ne peut pas être vide', __FILE__));
		}
	}

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function refresh() {
		DB::refresh($this);
	}

	/**
	 *
	 * @return boolean vrai si l'utilisateur est valide
	 */
	public function is_Connected() {
		return (is_numeric($this->id) && $this->login != '');
	}

	public function getDirectUrlAccess() {
		if (config::byKey('market::returnLink') != '' && config::byKey('market::allowDNS')) {
			return config::byKey('market::returnLink') . '&url=' . urlencode('/core/php/authentification.php?login=' . $this->getLogin() . '&smdp=' . $this->getPassword());
		}
		return config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80) . config::byKey('externalComplement') . '/core/php/authentification.php?login=' . $this->getLogin() . '&smdp=' . $this->getPassword();
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getLogin() {
		return $this->login;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		$this->options = utils::setJsonAttr($this->options, $_key, $_value);
	}

	public function getRights($_key = '', $_default = '') {
		return utils::getJsonAttr($this->rights, $_key, $_default);
	}

	public function setRights($_key, $_value) {
		$this->rights = utils::setJsonAttr($this->rights, $_key, $_value);
	}

	function getEnable() {
		return $this->enable;
	}

	function setEnable($enable) {
		$this->enable = $enable;
	}

}

?>
