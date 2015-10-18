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
	private $hash;

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
	public static function connect($_login, $_mdp, $_hash = false) {
		if (!$_hash) {
			$sMdp = sha1($_mdp);
		}
		if (config::byKey('ldap:enable') == '1' && !$_hash) {
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
						log::add('event', 'info', __('Connexion de l\'utilisateur ', __FILE__) . $_login);
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
		if (!$_hash) {
			$user = user::byLoginAndPassword($_login, $sMdp);
		} else {
			$user = user::byLoginAndHash($_login, $_mdp);
		}
		if (is_object($user)) {
			$user->getHash();
			$user->setOptions('lastConnection', date('Y-m-d H:i:s'));
			$user->save();
			jeedom::event('user_connect');
			log::add('event', 'info', __('Connexion de l\'utilisateur ', __FILE__) . $_login);
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

	public static function byHash($_hash) {
		$values = array(
			'hash' => $_hash,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM user
        WHERE hash=:hash';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byLoginAndHash($_login, $_hash) {
		$values = array(
			'login' => $_login,
			'hash' => $_hash,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		        FROM user
		        WHERE login=:login
		        AND hash=:hash';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byLoginAndPassword($_login, $_password) {
		$values = array(
			'login' => $_login,
			'password' => $_password,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		        FROM user
		        WHERE login=:login
		        AND password=:password';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
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
		$user->setPassword(config::genKey(45));
		$user->setRights('admin', 1);
		$user->setOptions('validity_limit', date('Y-m-d H:i:s', strtotime('+' . $_hours . ' hour now')));
		$user->save();
		return $user;
	}

	public static function hasDefaultIdentification() {
		$sql = 'SELECT count(id) as nb
        FROM user
        WHERE login="admin"
        AND password=SHA1("admin")
        AND `enable` = 1';
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
		return network::getNetworkAccess('external') . '/core/php/authentification.php?login=' . $this->getLogin() . '&hash=' . $this->getHash();
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

	public function getEnable() {
		return $this->enable;
	}

	public function setEnable($enable) {
		$this->enable = $enable;
	}

	public function getHash() {
		if ($this->hash == '' && $this->id != '') {
			$hash = sha1(config::genKey(128));
			while (is_object(self::byHash($hash))) {
				$hash = sha1(config::genKey(128));
			}
			$this->setHash($hash);
			$this->save();
		}
		return $this->hash;
	}

	public function setHash($hash) {
		$this->hash = $hash;
	}

}

?>
