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
require_once __DIR__ . '/../../core/php/core.inc.php';

use PragmaRX\Google2FA\Google2FA;

class user {
	/*     * *************************Attributs****************************** */

	private $id;
	private $login;
	private $profils = 'admin';
	private $password;
	private $options;
	private $rights;
	private $enable = 1;
	private ?string $hash = '';
	private $_changed = false;


	/*     * ***********************Méthodes statiques*************************** */

	/**
	 * Return user object if exists
	 *
	 * @param int|string $_id user ID
	 * @return user|null user object if exists, null otherwise
	 */
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
	 * Return user object if login and password are correct, else return false
	 * @param string $_login login
	 * @param string $_pswd password in plain text
	 * @return user|false object user or false if authentication fails
	 */
	public static function connect(string $_login, string $_pswd) {
		if (config::byKey('ldap:enable') == '1' && function_exists('ldap_connect')) {
			log::add("connection", "info", 'LDAP Authentication');
			$ad = ldap_connect(config::byKey('ldap:host'), config::byKey('ldap:port'));
			if (!$ad) {
				log::add("connection", "info", 'Connection LDAP Error');
				return false;
			}
			log::add("connection", "info", 'LDAP Connection OK');
			ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
			if (config::byKey('ldap:tls')) {
				if (!ldap_start_tls($ad)) {
					log::add("connection", "debug", 'start TLS KO');
					return false;
				} else {
					log::add("connection", "debug", 'start TLS OK');
				}
			}
			if (config::byKey('ldap:samba4')) {
				if (!ldap_bind($ad, $_login . '@' . config::byKey('ldap:domain'), $_pswd)) {
					log::add("connection", "info", 'LDAP bind user - login/password denied');
					return false;
				}
			} else {
				if (!ldap_bind($ad, config::byKey('ldap::usersearch') . '=' . $_login . ',' . config::byKey('ldap:basedn'), $_pswd)) {
					log::add("connection", "info", 'LDAP bind user - login/password denied');
					return false;
				}
			}
			log::add("connection", "debug", 'LDAP Bind user - OK');
			if (config::bykey('ldap:filter:admin') == "" && config::bykey('ldap:filter:user') == "" && config::bykey('ldap:filter:restrict') == "") {
				log::add("connection", "warning", 'LDAP Profile Check - [WARNING] None filter was set, "' . $_login . '"  authenticated as an administrator');
				$profile = 'admin';
			} else {
				foreach (array('admin', 'user', 'restrict', 'none') as $profile) {
					if ($profile == 'none') {
						log::add("connection", "warning", 'LDAP Profile Check - [WARNING] No profile has been set for "' . $_login . '" user in the LDAP');
						break;
					}
					if (config::bykey('ldap:filter:' . $profile) != "") {
						$filters = '(&(' . config::bykey('ldap::usersearch') . '=' . $_login . ')' . config::bykey('ldap:filter:' . $profile) . ')';
						log::add("connection", "debug", 'LDAP Profile Check - filter:, "' . $filters . '"');
						$result = ldap_search($ad, config::byKey('ldap:basedn'), $filters);
						$entries = ldap_get_entries($ad, $result);
						if ($entries['count'] > 0) {
							log::add("connection", "info", 'LDAP Profile Check - The "' . $profile . '" profile was FOUND for "' . $_login . '" user in the LDAP');
							break;
						}
					}
				}
			}
			if ($profile != 'none') {
				$user = self::byLogin($_login);
				if (is_object($user)) {
					$user->setPassword($_pswd)
						->setOptions('lastConnection', date('Y-m-d H:i:s'))
						->setProfils($profile);
					$user->save();
					return $user;
				}
				$user = (new user)
					->setLogin($_login)
					->setPassword($_pswd)
					->setOptions('lastConnection', date('Y-m-d H:i:s'))
					->setProfils($profile);
				$user->save();
				log::add("connection", "info", 'User created from the LDAP: ' . $_login);
				jeedom::event('user_connect', false, array('trigger_value' => $_login));  # FIXME: mips: user is NOT actually connected at this point, so this event is misleading. It should be moved after the checks on two factor authentication and localOnly.
				// TODO : if username == password => change ldap password
				log::add('event', 'info', 'User connection accepted: ' . $_login);
				return $user;
			} else {
				$user = self::byLogin($_login);
				if (is_object($user)) {
					$user->remove();
				}
				log::add("connection", "info", "User not allowed to access to Jeedom according to the LDAP ({$_login})");
			}
		}
		$user = self::byLogin($_login);
		if (is_object($user)) {
			$storedPassword = $user->getPassword();
			if (password_verify($_pswd, $storedPassword)) {
				if (password_needs_rehash($storedPassword, PASSWORD_DEFAULT)) {
					$user->setPassword($_pswd);
				}
			} elseif (hash_equals($storedPassword, sha512($_pswd))) {
				$user->setPassword($_pswd);
				log::add('audit', 'info', 'Password migrated from sha512 to native hash for: ' . $_login);
			} else {
				$user = false;
			}
		}
		if (is_object($user)) {
			$user->setOptions('lastConnection', date('Y-m-d H:i:s'));
			$user->save();
			jeedom::event('user_connect', false, array('trigger_value' => $_login)); # FIXME: mips: user is NOT actually connected at this point, so this event is misleading. It should be moved after the checks on two factor authentication and localOnly.
			log::add('event', 'info', 'Local account found for: ' . $_login);
		}
		return $user;
	}

	public static function connectToLDAP() {
		$ad = ldap_connect(config::byKey('ldap:host'), config::byKey('ldap:port'));
		ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
		if (config::byKey('ldap:tls') && !ldap_start_tls($ad)) {
			return false;
		}
		if (config::byKey('ldap:samba4')) {
			if (ldap_bind($ad, config::byKey('ldap:username') . "@" . config::byKey('ldap:domain'), config::byKey('ldap:password'))) {
				return $ad;
			}
		} else {
			if (ldap_bind($ad, config::byKey('ldap:username'), config::byKey('ldap:password'))) {
				return $ad;
			}
		}
		return false;
	}

	/**
	 * Get user by login
	 *
	 * @return user
	 */
	public static function byLogin($_login) {
		$values = array(
			'login' => $_login,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM user
		WHERE login=:login';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Get user by hash
	 *
	 * @return user
	 */
	public static function byHash($_hash) {
		$values = array(
			'hash' => $_hash,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM user';
		if (is_sha512($_hash)) {
			$sql .= ' WHERE SHA2(hash,512)=:hash';
		} else {
			$sql .= ' WHERE hash=:hash';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Get user by login and hash
	 *
	 * @return user
	 */
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

	/**
	 * Get all users
	 *
	 * @return user[] Array of all users
	 */
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM user ORDER by login';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Search users by right
	 *
	 * @return user[] Array of users with the specified right
	 */
	public static function searchByRight(string $_rights) {
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

	/**
	 * Search users by options
	 *
	 * @return user[] Array of users with the specified options
	 */
	public static function searchByOptions(string $_search) {
		$value = array(
			'search' => '%' . $_search . '%'
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM user
		WHERE options LIKE :search';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Search users by profils
	 *
	 * @return user[] Array of users with the specified profils
	 */
	public static function byProfils(string $_profils, bool $_enable = false) {
		$values = array(
			'profils' => $_profils,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM user
		WHERE profils=:profils';
		if ($_enable) {
			$sql .= ' AND enable=1';
		}
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 * Search users by enable status
	 *
	 * @return user[] Array of users with the specified enable status
	 */
	public static function byEnable(bool $_enable) {
		$values = array(
			'enable' => $_enable,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM user
		WHERE enable=:enable';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function failedLogin(): void {
		$current_ip = getClientIp();
		$failed_login = cache::byKey('security::failed_login::' . $current_ip);
		cache::set('security::failed_login::' . $current_ip, ($failed_login->getValue(0) + 1), config::byKey('security::timeLoginFailed'));
		if (($failed_login->getValue(0) + 1) > config::byKey('security::maxFailedLogin')) {
			$ban_ips = json_decode(cache::byKey('security::banip')->getValue('[]'), true);
			$ban_ips[$current_ip] = strtotime('now');
			cache::set('security::banip', json_encode($ban_ips));
		}
	}

	public static function removeBanIp(): void {
		$cache = cache::byKey('security::banip');
		$cache->remove();
	}

	public static function isBan(): bool {
		$current_ip = getClientIp();
		if ($current_ip == '') {
			return false;
		}
		$whiteIps = explode(';', config::byKey('security::whiteips'));
		if (is_array($whiteIps) && count($whiteIps) > 0) {
			foreach ($whiteIps as $whiteip) {
				if (netMatch($whiteip, $current_ip)) {
					return false;
				}
			}
		}
		$ban_ips = json_decode(cache::byKey('security::banip')->getValue('[]'), true);
		if (!is_array($ban_ips) || count($ban_ips) == 0) {
			return false;
		}
		if (count($ban_ips) > 0 && is_int(intval(config::byKey('security::bantime')))) {
			foreach ($ban_ips as $ip => $datetime) {
				if (!is_int(intval($datetime))) {
					continue;
				}
				if (intval(config::byKey('security::bantime')) == -1 || intval($datetime) + intval(config::byKey('security::bantime')) > strtotime('now')) {
					if ($ip == $current_ip) {
						jeedom::event('ip_ban', false, ['ip' => $ip, 'datetime' => intval($datetime)]);
						return true;
					}
					continue;
				}
				unset($ban_ips[$ip]);
			}
			cache::set('security::banip', json_encode($ban_ips));
		}
		return false;
	}

	public static function getAccessKeyForReport(): string {
		$user = user::byLogin('internal_report');
		if (!is_object($user)) {
			$user = new user();
			$user->setLogin('internal_report');
			$google2fa = new Google2FA();
			$user->setOptions('twoFactorAuthentificationSecret', $google2fa->generateSecretKey());
			$user->setOptions('twoFactorAuthentification', 1);
		}
		$user->setPassword(config::genKey(255));
		$user->setOptions('localOnly', 1);
		$user->setProfils('admin');
		$user->setEnable(1);
		$key = config::genKey();
		$registerDevice = array(
			sha512($key) => array(
				'datetime' => date('Y-m-d H:i:s'),
				'ip' => '127.0.0.1',
				'session_id' => 'none',
			),
		);
		$user->setOptions('registerDevice', $registerDevice);
		$user->save();
		log::audit('User internal_report created', [
			'login' => $user->getLogin(),
			'reason' => 'Report access enabled',
		]);
		try {
			$sessions = listSession();
			foreach ($sessions as $id => $session) {
				if ($session['user_id'] == $user->getId()) {
					deleteSession($id);
				}
			}
		} catch (\Exception $e) {
		}
		return $user->getHash() . '-' . $key;
	}

	public static function supportAccess($_enable = true): void {
		if ($_enable) {
			$user = user::byLogin('jeedom_support');
			if (!is_object($user)) {
				$user = new user();
				$user->setLogin('jeedom_support');
			}
			$user->setPassword(config::genKey(255));
			$user->setProfils('admin');
			$user->setEnable(1);
			$key = config::genKey();
			$registerDevice = array(
				sha512($key) => array(
					'datetime' => date('Y-m-d H:i:s'),
					'ip' => '127.0.0.1',
					'session_id' => 'none',
				),
			);
			$user->setOptions('registerDevice', $registerDevice);
			$user->save();
			log::audit('User jeedom_support created', [
				'login' => $user->getLogin(),
				'reason' => 'Support access enabled',
			]);
			repo_market::supportAccess(true, $user->getHash() . '-' . $key);
		} else {
			$user = user::byLogin('jeedom_support');
			if (is_object($user)) {
				$user->remove();
			}
			repo_market::supportAccess(false);
		}
	}

	public static function deadCmd(): array {
		$return = array();
		foreach ((user::all()) as $user) {
			$cmd = $user->getOptions('notification::cmd');
			if ($cmd != '') {
				if (!cmd::byId(str_replace('#', '', $cmd))) {
					$return[] = array('detail' => __('Utilisateur', __FILE__), 'help' => __('Commande notification utilisateur', __FILE__), 'who' => $cmd);
				}
			}
		}
		return $return;
	}

	public static function regenerateHashes() {
		foreach ((user::all()) as $user) {
			if ($user->getHash() != '') {
				if ($user->getProfils() != 'admin' || $user->getOptions('doNotRotateHash', 0) == 1 || $user->getEnable() == 0) {
					continue;
				}
				if (strtotime($user->getOptions('hashGenerated')) > strtotime('now -3 month')) {
					continue;
				}
			}
			$user->regenerateHash()->save();
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function preInsert(): void {
		if (is_object(self::byLogin($this->getLogin()))) {
			throw new Exception(__('Ce nom d\'utilisateur existe déjà', __FILE__));
		}
	}

	public function preSave(): void {
		if ($this->getLogin() == '') {
			throw new Exception(__('Le nom d\'utilisateur ne peut pas être vide', __FILE__));
		}
		if ($this->getPassword() == '') {
			throw new Exception(__('Le mot de passe ne peut pas être vide', __FILE__));
		}
		$admins = user::byProfils('admin', true);
		if (count($admins) == 1 && $admins[0]->getId() == $this->getId()) {
			if ($this->getProfils() == 'admin' && $this->getEnable() == 0) {
				throw new Exception(__('Vous ne pouvez pas désactiver le dernier utilisateur', __FILE__));
			}
			if ($this->getProfils() != 'admin') {
				throw new Exception(__('Vous ne pouvez pas changer le profil du dernier administrateur', __FILE__));
			}
		}
		if ($this->getHash() == '') {
			$this->regenerateHash();
		}
	}

	public function encrypt(): void {
		$this->getOptions('twoFactorAuthentification', utils::encrypt($this->getOptions('twoFactorAuthentification')));
	}

	public function decrypt(): void {
		$this->getOptions('twoFactorAuthentification', utils::decrypt($this->getOptions('twoFactorAuthentification')));
	}

	public function save(): bool {
		return DB::save($this);
	}

	public function preRemove(): void {
		if (count(user::byProfils('admin', true)) == 1 && ($this->getProfils() == 'admin' && $this->getEnable() == 1)) {
			throw new Exception(__('Vous ne pouvez pas supprimer le dernier administrateur', __FILE__));
		}
	}

	public function remove(): bool {
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getLogin(), 'date' => date('Y-m-d H:i:s'), 'type' => 'user'));
		return DB::remove($this);
	}

	public function refresh(): void {
		DB::refresh($this);
	}

	private function regenerateHash() {
		do {
			$_hash = config::genKey();
		} while (is_object(self::byHash($_hash)));

		$this->setHash($_hash);
		$this->setOptions('hashGenerated', date('Y-m-d H:i:s'));
		return $this;
	}

	/**
	 *
	 * @return boolean vrai si l'utilisateur est valide
	 */
	public function is_Connected(): bool {
		return (is_numeric($this->id) && $this->login != '');
	}

	public function validateTwoFactorCode($_code) {
		$google2fa = new Google2FA();
		return $google2fa->verifyKey($this->getOptions('twoFactorAuthentificationSecret'), $_code, 8);
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getLogin() {
		return $this->login;
	}

	public function getPassword(): string {
		return (string) $this->password;
	}

	public function setId($_id): self {
		$this->_changed = utils::attrChanged($this->_changed, $this->id, $_id);
		$this->id = $_id;
		return $this;
	}

	public function setLogin($_login): self {
		$this->_changed = utils::attrChanged($this->_changed, $this->login, $_login);
		$this->login = $_login;
		return $this;
	}

	public function setPassword(string $_password): self {
		if ($_password != '') {
			$_password = password_hash($_password, PASSWORD_DEFAULT);
		} else {
			throw new Exception(__('Le mot de passe ne peut pas être vide', __FILE__));
		}
		$this->_changed = utils::attrChanged($this->_changed, $this->password, $_password);
		$this->password = $_password;
		return $this;
	}

	public function getOptions($_key = '', $_default = '') {
		return utils::getJsonAttr($this->options, $_key, $_default);
	}

	public function setOptions($_key, $_value) {
		if ($_key == 'registerDevice' && is_array($_value) &&  count($_value) > 20) {
			uasort($_value, function ($a, $b) {
				return strtotime($b['datetime']) - strtotime($a['datetime']);
			});
			$_value = array_slice($_value, 0, 20, true);
		}
		$options = utils::setJsonAttr($this->options, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->options, $options);
		$this->options = $options;
		return $this;
	}

	public function getRights($_key = '', $_default = '') {
		return utils::getJsonAttr($this->rights, $_key, $_default);
	}

	public function setRights($_key, $_value): self {
		$rights = utils::setJsonAttr($this->rights, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->rights, $rights);
		$this->rights = $rights;
		return $this;
	}

	public function getEnable() {
		return $this->enable;
	}

	public function setEnable($_enable): self {
		$this->_changed = utils::attrChanged($this->_changed, $this->enable, $_enable);
		$this->enable = $_enable;
		return $this;
	}

	public function getHash(): string {
		return (string) $this->hash;
	}

	public function setHash(string $_hash) {
		if ($_hash == '') {
			return $this->regenerateHash();
		}
		$this->_changed = utils::attrChanged($this->_changed, $this->getHash(), $_hash);
		$this->hash = $_hash;
		return $this;
	}

	public function getProfils() {
		return $this->profils;
	}

	public function setProfils($_profils): self {
		$this->_changed = utils::attrChanged($this->_changed, $this->profils, $_profils);
		$this->profils = $_profils;
		return $this;
	}

	public function getChanged() {
		return $this->_changed;
	}

	public function setChanged($_changed): self {
		$this->_changed = $_changed;
		return $this;
	}
}
