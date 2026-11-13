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

class notification {
	public const CONFIG_KEY = 'notifications::settings';

	public const TYPES = ['timeout', 'batterywarning', 'batterydanger', 'warning', 'danger', 'system'];

	private static bool $sending = false;

	/** @return array{destinations: list<int>, types: list<string>} */
	public static function settings(): array {
		$settings = config::byKey(self::CONFIG_KEY);
		return [
			'destinations' => is_array($settings['destinations'] ?? null) ? $settings['destinations'] : [],
			'types' => is_array($settings['types'] ?? null) ? $settings['types'] : [],
		];
	}

	/** @return array{destinations: list<int>, types: list<string>} */
	public static function validateSettings($settings): array {
		$destinations = $settings['destinations'] ?? null;
		$types = $settings['types'] ?? null;
		if (!is_array($settings) || count($settings) !== 2 || !is_array($destinations) || !is_array($types)
			|| array_values($destinations) !== $destinations || array_values($types) !== $types) {
			throw new InvalidArgumentException(__('Configuration des notifications invalide.', __FILE__));
		}
		$current = $destinations !== [] ? self::settings()['destinations'] : [];
		foreach ($destinations as $id) {
			if (!is_int($id) || $id < 1) {
				throw new InvalidArgumentException(__('Identifiant de destination invalide.', __FILE__));
			}
			if (in_array($id, $current, true)) {
				continue;
			}
			$command = cmd::byId($id);
			if (!is_object($command) || $command->getType() !== 'action' || $command->getSubType() !== 'message') {
				throw new InvalidArgumentException(__('Choisissez une commande de type Message ou retirez la destination', __FILE__) . ' #' . $id . '#.');
			}
		}
		foreach ($types as $type) {
			if (!in_array($type, self::TYPES, true)) {
				throw new InvalidArgumentException(__('Type de notification inconnu.', __FILE__));
			}
		}
		return ['destinations' => array_values(array_unique($destinations)), 'types' => array_values(array_unique($types))];
	}

	public static function send(string $type, string $message): void {
		// Une commande de notification peut elle-même ajouter un message ou une erreur au journal.
		if (self::$sending) {
			return;
		}
		$settings = self::settings();
		if ($settings['destinations'] === [] || !in_array($type, $settings['types'], true)) {
			return;
		}
		$name = config::byKey('name', 'core', 'JEEDOM');
		$options = ['title' => '[' . $name . '] : ' . $message, 'message' => $name . ' : ' . $message];
		self::$sending = true;
		try {
			foreach ($settings['destinations'] as $id) {
				try {
					$command = cmd::byId($id);
					if (is_object($command) && $command->getType() === 'action' && $command->getSubType() === 'message') {
						$command->execCmd($options);
					}
				} catch (Throwable $e) {
					log::add('jeedomAlert', 'error', __('Erreur lors de l’envoi de la notification vers la commande', __FILE__)
						. ' #' . $id . '# : ' . log::exception($e));
				}
			}
		} finally {
			self::$sending = false;
		}
	}
}
