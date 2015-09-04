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

class evaluate {
	/* ---------------------------------------------------------------
	RENVOIE LA VALEUR REEL D'UN PARAMETRE
	--------------------------------------------------------------- */

	public function Get_Valeur_Of_Parametre($param) {
		$tabSignes = array("=", "!=", "&&", "||", "<", "<=", ">", ">=", "~", "!~", "+", "-", "%", "/", "^", "&", "*", '|^');
		if ((substr($param, 0, 1) == '"' || substr($param, 0, 1) == "'") && substr($param, -1, 1) == substr($param, 0, 1)) {
			$val = str_replace("\\\\", "\\", $param);
			$val = str_replace("\\" . substr($val, 0, 1), substr($val, 0, 1), $val);
			$val = substr($val, 1, strlen($val) - 2);
		} else {
			if (is_numeric(trim($param))) {
				$val = $param;
			} else {
				if (array_search($param, $tabSignes) !== false) {
					$val = $param;
				} else {
					$val = $param;
				}
			}
		}
		return $val;
	}

	/* ------------------------------------------------------------
	EVALUE UNE EXPRESSION MATHEMATIQUE OU LOGIQUE
	--------------------------------------------------------------- */

	public function Evaluer($chaine) {
		$replace = array(
			' et ' => ' && ',
			' ET ' => ' && ',
			' and ' => ' && ',
			' AND ' => ' && ',
			' ou ' => ' || ',
			' OU ' => ' || ',
			' or ' => ' || ',
			' OR ' => ' || ',
			' XOR ' => ' |^ ',
			' xor ' => ' |^ ',
		);
		$chaine = str_replace(array_keys($replace), array_values($replace), $chaine);

		$chaine = str_replace('==', '=', $chaine);
		$chaine = str_replace('!~', '§~', $chaine);
		$chaine = str_replace('!=', '§=', $chaine);
		$chaine = str_replace('!', '1 !', $chaine);
		$chaine = str_replace('§~', '!~', $chaine);
		$chaine = str_replace('§=', '!=', $chaine);
		$chaine = str_replace("\n", '', $chaine);

		//DECOMPOSITION DES PARAMETRES
		$lstParam = $this->Eval_Trouver_Liste_Param($chaine);
		//ERREUR SI UN OPERATEUR EST SITUE EN FIN DE CHAINE
		if (isset($lstParam[sizeof($lstParam) - 1]["operateur"])) {
			if ($lstParam[sizeof($lstParam) - 1]["operateur"]) {
				throw new Exception(__("ERREUR opérateur inattendu en fin d'expression", __FILE__));
			}
		}
		//PARCOUR DES PARAMETRES
		for ($i = 0; $i < sizeof($lstParam); $i++) {
			//OPERATEUR SPECIAL
			if (isset($lstParam[$i]["operateur"]) && $lstParam[$i]["operateur"] == "|") {
				throw new Exception(__("ERREUR opérateur '|' Inconnu", __FILE__));
			}
			//CAS DES ( et des {
			if (substr($lstParam[$i]["valeur"], 0, 1) == "(" && substr($lstParam[$i]["valeur"], -1, 1) == ")") {
				$lstParam[$i]["valeur"] = $this->Evaluer(substr($lstParam[$i]["valeur"], 1, strlen($lstParam[$i]["valeur"]) - 2));
			} else {
				if (substr($lstParam[$i]["valeur"], 0, 1) == "{" && substr($lstParam[$i]["valeur"], -1, 1) == "}") {
					$lstParam[$i]["valeur"] = $this->Evaluer(substr($lstParam[$i]["valeur"], 1, strlen($lstParam[$i]["valeur"]) - 2));
				}
			}
			//ON PREND LA VALEUR REEL DU PARAMETRE
			$lstParam[$i]["valeur"] = $this->Get_Valeur_Of_Parametre($lstParam[$i]["valeur"]);
		}
		return $this->Eval_Evaluer_Liste_Parametres($lstParam);
	}

	/* ------------------------------------------------------------
	EVALUE UNE LISTE DE PARAMETRE(VALEUR+OPERATEUR)
	--------------------------------------------------------------- */

	private function Eval_Evaluer_Liste_Parametres($lstParam) {
		$tabOperateursComparaison = array('&&', '||', '|^', '=', '!=', '<', '<=', '>', '>=', '~', '!~');
		$tabOperateursoperation = array('&', '^', '%', '*', '/', '-', '!', '+');

		//ON CHERCHE UN EVENTUEL OPERATEUR DE COMPARAISON
		$trouve = false;
		$i = 0;
		while (!$trouve && $i < sizeof($tabOperateursComparaison)) {
			$j = 0;
			while (!$trouve && $j < sizeof($lstParam)) {
				if (isset($lstParam[$j]["operateur"]) && $lstParam[$j]["operateur"] == $tabOperateursComparaison[$i]) {
					$trouve = true;
				} else {
					$j++;
				}
			}
			$i++;
		}
		if ($trouve) {
			//ON COMPARE
			$tab1 = array();
			$tab2 = array();
			for ($i = 0; $i < sizeof($lstParam); $i++) {
				if ($i <= $j) {
					$tab1[$i] = $lstParam[$i];
				}
				if ($i > $j) {
					$tab2[$i - ($j + 1)] = $lstParam[$i];
				}
			}
			$tab1[$j]["operateur"] = "";
			$res = $this->Eval_Comparer($this->Eval_Evaluer_Liste_Parametres($tab1), $this->Eval_Evaluer_Liste_Parametres($tab2), $lstParam[$j]["operateur"]);
		} else {
			//SINON ON CALCUL
			while (sizeof($lstParam) > 1) {
				if (isset($lstParam[0]["operateur"]) && in_array($lstParam[0]["operateur"], $tabOperateursoperation)) {
					$lstParam[1]["valeur"] = $this->Eval_Faire_Operation($lstParam[0]["valeur"], $lstParam[1]["valeur"], $lstParam[0]["operateur"]);
				}
				array_shift($lstParam);
			}
			$res = (isset($lstParam[0]["valeur"])) ? $lstParam[0]["valeur"] : '';
		}
		return $res;
	}

	/* ------------------------------------------------------------
	EFFECTUE UNE OPERATION SIMPLE ENTRE DEUX VALEURS
	--------------------------------------------------------------- */

	private function Eval_Faire_Operation($valeur1, $valeur2, $operateur) {
		if ($operateur != "&" && $operateur != "!" && (!is_numeric($valeur1) || !is_numeric($valeur2))) {
			throw new Exception(__('ERREUR attention l\'opérateur ', __FILE__) . $operateur . __(' nécessite deux opérandes numériques.', __FILE__));
		}
		$valeur1 = trim($valeur1);
		$valeur2 = trim($valeur2);
		switch ($operateur) {
			case "+":
				$res = $valeur1 + $valeur2;
				break;
			case "-":

				$res = $valeur1 - $valeur2;
				break;
			case "*":
				$res = $valeur1 * $valeur2;
				break;
			case "/":
				$res = $valeur1 / $valeur2;
				break;
			case "%":
				$res = $valeur1 % $valeur2;
				break;
			case "!":
				$res = (!$valeur2) ? 1 : 0;
				break;
			case "&":
				$res = $valeur1 . $valeur2;
				break;
			case "^":
				$res = pow($valeur1, $valeur2);
				break;
		}

		return $res;
	}

	/* ------------------------------------------------------------
	COMPARE DEUX VALEURS EN FONCTION DE L'OPERATEUR
	--------------------------------------------------------------- */

	private function Eval_Comparer($valeur1, $valeur2, $operateur) {
		$valeur1 = trim($valeur1);
		$valeur2 = trim($valeur2);
		switch ($operateur) {
			case "=":
				if (!is_numeric($valeur1) && !is_numeric($valeur2)) {
					if (strcasecmp(strtolower($valeur1), strtolower($valeur2)) == 0) {
						$res = true;
					} else {
						$res = false;
					}
				} else {
					if ($valeur1 == $valeur2) {
						$res = true;
					} else {
						$res = false;
					}
				}

				break;
			case "<":
				if ($valeur1 < $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "<=":
				if ($valeur1 <= $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case ">":
				if ($valeur1 > $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case ">=":
				if ($valeur1 >= $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "!=":
				if ($valeur1 != $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "||":
				if ($valeur1 || $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "&&":

				if ($valeur1 && $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "|^":
				if ($valeur1 XOR $valeur2) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "~":
				if (strpos(strtolower($valeur1), strtolower($valeur2)) !== false) {
					$res = true;
				} else {
					$res = false;
				}
				break;
			case "!~":
				if (strpos(strtolower($valeur1), strtolower($valeur2)) === false) {
					$res = true;
				} else {
					$res = false;
				}
				break;
		}
		return $res;
	}

	/* ------------------------------------------------------------
	SEPARE LES PARAMETRES (VALEUR,OPERATEUR) DEPUIS UNE CHAINE
	--------------------------------------------------------------- */

	private function Eval_Trouver_Liste_Param($param) {
		$tabSignes = array("=", "!=", "&&", "||", "<", "<=", ">", ">=", "+", "-", "%", "/", "^", "&", "*", "|", "~", "!~", "!", "|^");

		$param = trim($param);
		$lstP = array();

		$nb = 0;
		$caractereSpeciale = false;
		$caracOuvrant = array();
		$nbCaractOuvrant = 0;
		$lastNum = -1;
		$caracOuvrant[0] = "";
		$paramNom = "";
		//PARCOUR DE LA CHAINE
		for ($i = 0; $i < strlen($param); $i++) {
			$lettre = substr($param, $i, 1);
			//CARACTERE OUVRANT ET FERMANT " ' {
			if ($lettre == "\\") {
				$caractereSpeciale = !$caractereSpeciale;
			} else {
				if (!$caractereSpeciale) {
					if ($caracOuvrant[$nbCaractOuvrant] == $lettre) {
						unset($caracOuvrant[$nbCaractOuvrant]);
						$nbCaractOuvrant--;
					} else {
						if ($lettre == '{') {
							$caracOuvrant[$nbCaractOuvrant + 1] = "}";
							$nbCaractOuvrant++;
						} else if ($lettre == '(') {
							if ($caracOuvrant[$nbCaractOuvrant] != "'" && $caracOuvrant[$nbCaractOuvrant] != '"') {
								$caracOuvrant[$nbCaractOuvrant + 1] = ")";
								$nbCaractOuvrant++;
							}
						} else if (($lettre == '"' && $caracOuvrant[$nbCaractOuvrant] != "'") || ($lettre == "'" && $caracOuvrant[$nbCaractOuvrant] != '"')) {
							$caracOuvrant[$nbCaractOuvrant + 1] = $lettre;
							$nbCaractOuvrant++;
						}
					}
				}
				$caractereSpeciale = false;
			}
			//SEPARATION DES PARAMETRES
			if ((array_search($lettre, $tabSignes) !== false || $lettre == " ") && $nbCaractOuvrant == 0) {
				if (array_search($lettre, $tabSignes) !== false) {
					if ($paramNom != "") {
						if (!isset($lstP[$lastNum]["operateur"]) && $lastNum != -1) {
							throw new Exception(__("ERREUR deux paramètres sans signe de séparation : ", __FILE__) . $param);
						}
						$num = sizeof($lstP);
						$lstP[$num] = array();
						$lstP[$num]["valeur"] = $paramNom;
						$lstP[$num]["operateur"] = $lettre;
						$lastNum = $num;
						$paramNom = "";
					} else {
						if (isset($lstP[$lastNum]["operateur"]) && $lastNum != -1) {
							$ope = $lstP[$lastNum]["operateur"] . $lettre;

							if (array_search($ope, $tabSignes) === false) {
								if ($lettre != '-') {
									throw new Exception(__("ERREUR deux opérations à la suite", __FILE__));
								} else {
									$paramNom = '-' . $paramNom;
								}
							} else {
								$lstP[$lastNum]["operateur"] .= $lettre;
							}
						} else {
							if ($lastNum == -1) {
								if ($lettre != '-') {
									throw new Exception(__("ERREUR l'expression attend un paramètre avant symbole : " . $lettre, __FILE__));
								} else {
									$paramNom = '-' . $paramNom;
								}
							} else {
								$lstP[$lastNum]["operateur"] = $lettre;
							}
						}
					}
				} else {
					if ($lastLettre != " " && array_search($lastLettre, $tabSignes) === false) {
						if (!isset($lstP[$lastNum]["operateur"]) && $lastNum != -1) {
							throw new Exception(__("ERREUR deux paramètres sans signe de séparation", __FILE__));
						}
						$num = sizeof($lstP);
						$lstP[$num] = array();
						$lstP[$num]["valeur"] = $paramNom;
						$lastNum = $num;
						$paramNom = "";
					}
				}
			} else {
				$paramNom .= $lettre;
			}
			$lastLettre = $lettre;
		}
		//DERNIER PARAMETRE

		if ($paramNom != "") {
			$num = sizeof($lstP);
			$lstP[$num] = array();
			$lstP[$num]["valeur"] = $paramNom;
			$lstP[$num]["operateur"] = "";
		}
		//ERREUR SI UN CARACTERE OUVRANT " ' ( ou { n'a pas été fermé
		if ($nbCaractOuvrant > 0) {
			if (isset($caracOuvrant[sizeof($caracOuvrant)])) {
				throw new Exception(__("ERREUR dans l'espression, caractère fermant attendu : ", __FILE__) . $caracOuvrant[sizeof($caracOuvrant)]);
			} else {
				throw new Exception(__("ERREUR dans l'espression, caractère fermant attendu : ", __FILE__));
			}
		}
		return $lstP;
	}

}

?>
