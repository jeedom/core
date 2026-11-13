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

/**
 * Menus latéraux nommés de la nouvelle interface.
 *
 * `ui::menus` garde la liste des menus (`{default, menus: [{id, name, items?}]}`) ;
 * les entrées du menu par défaut restent dans l'ancienne clé `ui::menu`, ce qui
 * reprend sans migration le menu commun existant. L'option utilisateur `menu`
 * désigne le menu attribué (absente : menu par défaut).
 *
 * Erreurs : InvalidArgumentException (données invalides), OutOfBoundsException
 * (menu inconnu), LengthException (nom déjà pris), DomainException (menu par
 * défaut non supprimable).
 */
class uiMenu {
	public const MAX_MENUS = 50;

	/** Chemins de l'interface réservés aux administrateurs (`meta.requiresAdmin` du routeur de Core UI). */
	private const ADMIN_PATHS = [
		'#^/eqLogics/new(/|$)#', '#^/scenarios/(?!$)#', '#^/crons(/|$)#', '#^/notes(/|$)#', '#^/backups(/|$)#',
		'#^/logs(/|$)#', '#^/advanced-tools(/|$)#', '#^/users(/|$)#', '#^/config(/|$)#', '#^/settings/menu(/|$)#',
		'#^/plugins(/|$)#', '#^/plugin-details(/|$)#',
	];

	/** @return list<array{id: string, name: string, isDefault: bool, items: ?array, users: list<int>}> */
	public static function all(): array {
		$state = self::state();
		$users = self::assignments($state);
		$result = array();
		foreach ($state['menus'] as $menu) {
			$result[] = self::export($state, $menu, $users);
		}
		usort($result, static fn ($a, $b) => (int) $b['isDefault'] - (int) $a['isDefault']);
		return $result;
	}

	public static function byId(string $id): array {
		$state = self::state();
		return self::export($state, self::find($state, $id), self::assignments($state));
	}

	public static function create(string $name, mixed $items = null, ?string $copyFrom = null): array {
		$state = self::state();
		if (count($state['menus']) >= self::MAX_MENUS) {
			throw new InvalidArgumentException('Trop de menus.');
		}
		$name = self::checkName($state, $name, null);
		if ($copyFrom !== null) {
			$items = self::itemsOf($state, self::find($state, $copyFrom));
		} else {
			$items = $items === null ? null : self::validateItems($items, true);
		}
		do {
			$id = bin2hex(random_bytes(4));
		} while (self::indexOf($state, $id) !== null);
		$state['menus'][] = array('id' => $id, 'name' => $name, 'items' => $items);
		self::store($state);
		return self::byId($id);
	}

	/** @param array{name?: string, items?: ?array, isDefault?: bool} $changes */
	public static function update(string $id, array $changes): array {
		$state = self::state();
		$index = self::indexOf($state, $id);
		if ($index === null) {
			throw new OutOfBoundsException('Menu inconnu.');
		}
		if (array_key_exists('name', $changes)) {
			$state['menus'][$index]['name'] = self::checkName($state, $changes['name'], $id);
		}
		if (array_key_exists('items', $changes)) {
			$items = $changes['items'] === null ? null : self::validateItems($changes['items'], true);
			if ($state['default'] === $id) {
				config::save('ui::menu', $items ?? '');
			} else {
				$state['menus'][$index]['items'] = $items;
			}
		}
		if (array_key_exists('isDefault', $changes) && $state['default'] !== $id) {
			$previous = self::indexOf($state, $state['default']);
			$state['menus'][$previous]['items'] = self::itemsOf($state, $state['menus'][$previous]);
			config::save('ui::menu', $state['menus'][$index]['items'] ?? '');
			unset($state['menus'][$index]['items']);
			$state['default'] = $id;
		}
		self::store($state);
		return self::byId($id);
	}

	public static function remove(string $id): void {
		$state = self::state();
		$index = self::indexOf($state, $id);
		if ($index === null) {
			throw new OutOfBoundsException('Menu inconnu.');
		}
		if ($state['default'] === $id) {
			throw new DomainException('Le menu par défaut ne peut pas être supprimé.');
		}
		array_splice($state['menus'], $index, 1);
		self::store($state);
		foreach (user::all() as $user) {
			if ($user->getOptions('menu', '') === $id) {
				$user->setOptions('menu', '');
				$user->save();
			}
		}
	}

	public static function exists(string $id): bool {
		return self::indexOf(self::state(), $id) !== null;
	}

	/**
	 * Menu effectif d'un utilisateur : son menu attribué, sinon le menu par défaut ;
	 * pour un non-administrateur, entrées d'administration et sections vidées retirées.
	 */
	public static function effective(user $user): array {
		$state = self::state();
		$assigned = (string) $user->getOptions('menu', '');
		$index = $assigned === '' ? null : self::indexOf($state, $assigned);
		$menu = $state['menus'][$index ?? self::indexOf($state, $state['default'])];
		$items = self::itemsOf($state, $menu);
		if ($items !== null && $user->getProfils() !== 'admin') {
			$items = self::withoutAdminEntries($items);
		}
		return array('id' => $menu['id'], 'name' => $menu['name'], 'items' => $items);
	}

	/**
	 * Valide une liste d'entrées de menu et la renvoie normalisée.
	 *
	 * @return list<array<string, mixed>>
	 */
	public static function validateItems(mixed $items, bool $root, array &$ids = array()): array {
		$fail = static fn (string $message) => throw new InvalidArgumentException($message);
		if (!is_array($items) || !array_is_list($items)) {
			$fail('Une liste d’entrées est attendue.');
		}
		if (count($items) > ($root ? 200 : 100)) {
			$fail('Trop d’entrées dans le menu.');
		}
		$result = array();
		foreach ($items as $item) {
			if (!is_array($item) || array_diff(array_keys($item), array('id', 'type', 'visible', 'name', 'icon', 'url', 'children')) !== array()) {
				$fail('Entrée invalide.');
			}
			$id = $item['id'] ?? null;
			if (!is_string($id) || !preg_match('/\A[A-Za-z0-9_:.-]{1,100}\z/', $id) || isset($ids[$id])) {
				$fail('Identifiant d’entrée invalide ou en double.');
			}
			$ids[$id] = true;
			$type = $item['type'] ?? null;
			if (!in_array($type, array('page', 'link', 'section'), true) || !is_bool($item['visible'] ?? null)) {
				$fail("Type ou visibilité invalide pour l’entrée $id.");
			}
			$name = $item['name'] ?? null;
			if ($name !== null && (!is_string($name) || mb_strlen($name) > 100)) {
				$fail("Nom invalide pour l’entrée $id.");
			}
			if ($type !== 'page' && ($name === null || trim($name) === '')) {
				$fail("Un nom est obligatoire pour l’entrée $id.");
			}
			$icon = $item['icon'] ?? null;
			if ($icon !== null && (!is_string($icon) || !preg_match('/\A[A-Za-z0-9_: -]{0,100}\z/', $icon))) {
				$fail("Icône invalide pour l’entrée $id.");
			}
			$entry = array('id' => $id, 'type' => $type, 'visible' => $item['visible'], 'name' => $name, 'icon' => $icon);
			if ($type === 'link') {
				$url = $item['url'] ?? null;
				if (!is_string($url) || !self::isMenuUrl($url)) {
					$fail("Lien invalide pour l’entrée $id : URL http(s) ou chemin interne attendu.");
				}
				$entry['url'] = $url;
			} elseif (array_key_exists('url', $item)) {
				$fail("Seule une entrée de type lien a une URL ($id).");
			}
			if ($type === 'section') {
				if (!$root) {
					$fail("Une section ne peut pas contenir de section ($id).");
				}
				$entry['children'] = self::validateItems($item['children'] ?? array(), false, $ids);
			} elseif (array_key_exists('children', $item)) {
				$fail("Seule une section a des sous-entrées ($id).");
			}
			$result[] = $entry;
		}
		return $result;
	}

	private static function isMenuUrl(string $url): bool {
		if (strlen($url) > 2048 || preg_match('/[\x00-\x20\x7f\\\\]/', $url)) {
			return false;
		}
		if (str_starts_with($url, '/')) {
			return !str_starts_with($url, '//');
		}
		return filter_var($url, FILTER_VALIDATE_URL) !== false
			&& in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), array('http', 'https'), true);
	}

	private static function withoutAdminEntries(array $items): array {
		$result = array();
		foreach ($items as $item) {
			if ($item['type'] === 'link' && self::isAdminPath($item['url'])) {
				continue;
			}
			if ($item['type'] === 'section') {
				$children = self::withoutAdminEntries($item['children']);
				if ($children === array() && $item['children'] !== array()) {
					continue;
				}
				$item['children'] = $children;
			}
			$result[] = $item;
		}
		return $result;
	}

	private static function isAdminPath(string $url): bool {
		if (!str_starts_with($url, '/')) {
			return false;
		}
		$path = rtrim((string) parse_url($url, PHP_URL_PATH), '/');
		foreach (self::ADMIN_PATHS as $pattern) {
			if (preg_match($pattern, $path)) {
				return true;
			}
		}
		return false;
	}

	/** État enregistré ; créé au premier accès avec l'ancien `ui::menu` comme menu par défaut. */
	private static function state(): array {
		$state = config::byKey('ui::menus');
		if (is_array($state) && is_string($state['default'] ?? null) && is_array($state['menus'] ?? null)
			&& self::indexOf($state, $state['default']) !== null) {
			return $state;
		}
		$id = bin2hex(random_bytes(4));
		$state = array('default' => $id, 'menus' => array(array('id' => $id, 'name' => 'Par défaut')));
		self::store($state);
		return $state;
	}

	private static function store(array $state): void {
		$state['menus'] = array_values($state['menus']);
		config::save('ui::menus', $state);
	}

	private static function indexOf(array $state, string $id): ?int {
		foreach ($state['menus'] as $index => $menu) {
			if (($menu['id'] ?? null) === $id) {
				return $index;
			}
		}
		return null;
	}

	private static function find(array $state, string $id): array {
		$index = self::indexOf($state, $id);
		if ($index === null) {
			throw new OutOfBoundsException('Menu inconnu.');
		}
		return $state['menus'][$index];
	}

	private static function itemsOf(array $state, array $menu): ?array {
		$items = $menu['id'] === $state['default'] ? config::byKey('ui::menu') : ($menu['items'] ?? null);
		if (!is_array($items)) {
			return null;
		}
		try {
			return self::validateItems($items, true);
		} catch (InvalidArgumentException) {
			return null;
		}
	}

	private static function checkName(array $state, mixed $name, ?string $id): string {
		if (!is_string($name) || trim($name) === '' || mb_strlen($name) > 50) {
			throw new InvalidArgumentException('Nom de menu invalide (1 à 50 caractères).');
		}
		$name = trim($name);
		foreach ($state['menus'] as $menu) {
			if ($menu['id'] !== $id && mb_strtolower($menu['name']) === mb_strtolower($name)) {
				throw new LengthException('Un menu porte déjà ce nom.');
			}
		}
		return $name;
	}

	/** @return array<string, list<int>> identifiants d'utilisateurs par menu attribué */
	private static function assignments(array $state): array {
		$result = array();
		foreach (user::all() as $user) {
			$menu = (string) $user->getOptions('menu', '');
			if ($menu !== '') {
				$result[$menu][] = (int) $user->getId();
			}
		}
		return $result;
	}

	private static function export(array $state, array $menu, array $users): array {
		return array(
			'id' => $menu['id'],
			'name' => $menu['name'],
			'isDefault' => $menu['id'] === $state['default'],
			'items' => self::itemsOf($state, $menu),
			'users' => $users[$menu['id']] ?? array(),
		);
	}
}
