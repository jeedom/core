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
 * Résolution de l'adresse du client derrière des proxys de confiance (réglage
 * network::trustedProxies). Sans dépendance au reste du Core : utilisable avant
 * le bootstrap (aucun proxy n'est alors de confiance) et par l'API REST.
 */
class trustedProxy {
	/** En-têtes à valeur unique lus d'un pair de confiance, à défaut de X-Forwarded-For. */
	private const FALLBACK_HEADERS = array('HTTP_X_REAL_IP', 'HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP');

	/**
	 * Adresse du client pour une requête décrite à la manière de $_SERVER, ou '' sans pair
	 * valide (CLI, cron). Un pair qui n'est pas de confiance est le client. Sinon
	 * X-Forwarded-For est remonté de droite à gauche tant que l'adresse est de confiance ;
	 * une entrée illisible arrête la remontée sur la dernière adresse valide. Ce n'est que
	 * si la chaîne n'apporte aucune adresse (absente, ou entrée la plus à droite illisible)
	 * que X-Real-IP, CF-Connecting-IP puis Client-IP sont lus : un proxy qui ajoute le pair
	 * à X-Forwarded-For sans filtrer ces en-têtes ne laisse ainsi pas le client choisir.
	 */
	public static function clientIp(array $_server): string {
		$peer = self::normalizeIp((string) ($_server['REMOTE_ADDR'] ?? ''));
		if ($peer === null) {
			return '';
		}
		if (!self::isTrusted($peer)) {
			return $peer;
		}
		$client = self::walkForwardedFor((string) ($_server['HTTP_X_FORWARDED_FOR'] ?? ''));
		if ($client !== null) {
			return $client;
		}
		foreach (self::FALLBACK_HEADERS as $header) {
			$value = explode(',', (string) ($_server[$header] ?? ''))[0];
			$address = self::normalizeIp(trim($value));
			if ($address !== null) {
				return $address;
			}
		}
		return $peer;
	}

	public static function isTrusted(string $_address): bool {
		$address = self::normalizeIp($_address);
		if ($address === null) {
			return false;
		}
		$binary = (string) inet_pton($address);
		foreach (self::ranges() as [$network, $prefix]) {
			if (strlen($network) !== strlen($binary)) {
				continue;
			}
			$bytes = intdiv($prefix, 8);
			if (substr($binary, 0, $bytes) !== substr($network, 0, $bytes)) {
				continue;
			}
			$rest = $prefix % 8;
			if ($rest === 0) {
				return true;
			}
			$mask = (0xff << (8 - $rest)) & 0xff;
			if ((ord($binary[$bytes]) & $mask) === (ord($network[$bytes]) & $mask)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Valide une liste de proxys (IP ou CIDR IPv4/IPv6, séparés par « ; », « , » ou des espaces)
	 * et la renvoie normalisée, séparée par « ; ».
	 *
	 * @throws InvalidArgumentException sur la première entrée invalide
	 */
	public static function normalizeList(string $_value): string {
		$entries = array();
		foreach (preg_split('/[\s;,]+/', $_value, -1, PREG_SPLIT_NO_EMPTY) as $entry) {
			if (self::parseRange($entry) === null) {
				throw new InvalidArgumentException($entry);
			}
			$entries[] = $entry;
		}
		return implode(';', array_values(array_unique($entries)));
	}

	/** Adresse IP canonique (IPv4 mappée en IPv6 ramenée en IPv4), ou null si invalide. */
	public static function normalizeIp(string $_address): ?string {
		if (!filter_var($_address, FILTER_VALIDATE_IP)) {
			return null;
		}
		$binary = inet_pton($_address);
		if ($binary === false) {
			return null;
		}
		if (strlen($binary) === 16 && str_starts_with($binary, str_repeat("\0", 10) . "\xff\xff")) {
			$binary = substr($binary, 12);
		}
		return (string) inet_ntop($binary);
	}

	private static function walkForwardedFor(string $_chain): ?string {
		$client = null;
		foreach (array_reverse(explode(',', $_chain)) as $address) {
			$address = self::normalizeIp(trim($address));
			if ($address === null) {
				break;
			}
			$client = $address;
			if (!self::isTrusted($client)) {
				break;
			}
		}
		return $client;
	}

	/**
	 * Plages de confiance : JEEDOM_TRUSTED_PROXIES si la variable est définie, sinon la clé
	 * network::trustedProxies. Avant le bootstrap du Core, aucun proxy n'est de confiance.
	 * Les entrées invalides sont ignorées.
	 *
	 * @return list<array{string, int}>
	 */
	private static function ranges(): array {
		static $cache = array();
		$value = getenv('JEEDOM_TRUSTED_PROXIES');
		if ($value === false || trim($value) === '') {
			$value = class_exists('config', false) ? config::byKey('network::trustedProxies') : '';
		}
		$value = is_string($value) ? $value : '';
		if (!isset($cache[$value])) {
			$ranges = array();
			foreach (preg_split('/[\s;,]+/', $value, -1, PREG_SPLIT_NO_EMPTY) as $entry) {
				$range = self::parseRange($entry);
				if ($range !== null) {
					$ranges[] = $range;
				}
			}
			$cache = array($value => $ranges);
		}
		return $cache[$value];
	}

	/** @return array{string, int}|null adresse binaire et longueur du préfixe */
	private static function parseRange(string $_entry): ?array {
		$parts = explode('/', $_entry, 2);
		$address = self::normalizeIp($parts[0]);
		if ($address === null) {
			return null;
		}
		$binary = (string) inet_pton($address);
		$bits = strlen($binary) * 8;
		if (!isset($parts[1])) {
			return array($binary, $bits);
		}
		if (!preg_match('/\A[0-9]{1,3}\z/', $parts[1])) {
			return null;
		}
		$prefix = (int) $parts[1];
		// Un préfixe ::ffff:a.b.c.d/n désigne une plage IPv4.
		if ($bits === 32 && str_contains($parts[0], ':')) {
			$prefix -= 96;
		}
		return $prefix >= 0 && $prefix <= $bits ? array($binary, $prefix) : null;
	}
}
