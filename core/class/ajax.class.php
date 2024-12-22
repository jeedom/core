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

/**
 * Gère les réponses AJAX de Jeedom
 *
 * @note Évolutions possibles et compatibles
 *   Cette classe pourrait être enrichie progressivement avec :
 *   - Une interface ResponseFormatterInterface pour supporter différents formats (json, xml...)
 *   - Un système de middleware pour la validation des entrées
 *   - Des codes d'erreur HTTP standards via une énumération
 *   Ces changements peuvent être implémentés graduellement sans casser l'existant
 *
 * @example Utilisation actuelle
 * ```php
 * ajax::init(['getInfos']);
 * ajax::success($data);
 * ```
 *
 * @example Utilisation future possible
 * ```php
 * // Même API, plus de fonctionnalités
 * ajax::init(['getInfos'])
 *    ->withValidator(new InputValidator())
 *    ->withFormat(new JsonFormatter());
 * ajax::success($data);
 * ```
 *
 * @see config::class Pour la gestion des configurations
 * @see log::class Pour la gestion des logs
 *
 * @todo Version 4.6 ou 5.0
 *   - [OPTIONNEL] Ajouter un système de middleware pour valider les entrées
 *   - [OPTIONNEL] Support de différents formats via interfaces
 *   - [COMPATIBLE] Utiliser des codes HTTP standards
 */
class ajax {
	/*     * *************************Attributs****************************** */

	/*     * *********************Methode static ************************* */

    /**
     * Initialise la réponse AJAX
     * Configure les en-têtes HTTP et vérifie les actions autorisées en GET
     *
     * @param array $_allowGetAction Liste des actions autorisées en GET
     * @return void
     * @throws \Exception Si l'action demandée en GET n'est pas autorisée
     *
     * @note Évolution possible
     * Cette méthode pourrait retourner $this pour permettre le chaînage :
     * ```php
     * ajax::init(['action'])
     *     ->withValidator()
     *     ->withFormat();
     * ```
     * Ce changement serait rétrocompatible
     */
	public static function init($_allowGetAction = array()) {
		if (!headers_sent()) {
			header('Content-Type: application/json');
		}
		if(isset($_GET['action']) && !in_array($_GET['action'], $_allowGetAction)){
			throw new \Exception(__('Méthode non autorisée en GET : ',__FILE__).$_GET['action']);
		}
	}

    /**
     * Retourne un token (méthode non utilisée ?)
     *
     * @return string Token vide
     */
	public static function getToken(){
		return '';
	}

    /**
     * Envoie une réponse de succès et termine l'exécution
     *
     * @param mixed $_data Données à renvoyer dans la réponse
     * @return never
     *
     * @note Compatibilité et évolution
     * Pour maintenir la compatibilité tout en permettant l'évolution :
     * - Garder le comportement actuel par défaut
     * - Permettre l'injection d'un formatter optionnel
     * ```php
     * ajax::success($data, new JsonFormatter()); // Optionnel
     * ```
     */
	public static function success($_data = '') {
		echo self::getResponse($_data);
		die();
	}

    /**
     * Envoie une réponse d'erreur et termine l'exécution
     *
     * @param mixed $_data Message d'erreur ou données à renvoyer
     * @param int $_errorCode Code d'erreur
     * @return never
     */
	public static function error($_data = '', $_errorCode = 0) {
		echo self::getResponse($_data, $_errorCode);
		die();
	}

    /**
     * Génère la réponse JSON formatée
     *
     * @param mixed $_data Données à inclure dans la réponse
     * @param ?int $_errorCode Code d'erreur (null pour une réponse de succès)
     * @return string Réponse JSON encodée
     *
     * @note Architecture future
     * Cette méthode pourrait déléguer le formatage à des classes dédiées :
     * - JsonFormatter (comportement actuel)
     * - XmlFormatter
     * - CsvFormatter
     * etc.
     * La transition peut se faire graduellement en gardant le comportement par défaut
     */
	public static function getResponse($_data = '', $_errorCode = null) {
		$isError = !(null === $_errorCode);
		$return = array(
			'state' => $isError ? 'error' : 'ok',
			'result' => $_data,
		);
		if ($isError) {
			$return['code'] = $_errorCode;
		}
		return json_encode($return, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
	}
	/*     * **********************Getteur Setteur*************************** */
}
