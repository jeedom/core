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

class interactQuery {
    /*     * *************************Attributs****************************** */

    private $id;
    private $interactDef_id;
    private $query;
    private $link_type;
    private $link_id;
    private $enable = 1;

    /*     * ***********************Méthodes statiques*************************** */

    public static function byId($_id) {
        $values = array(
            'id' => $_id
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactQuery
        WHERE id=:id';

        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byQuery($_query,$_interactDef_id = null) {
        $values = array(
            'query' => $_query
            );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM interactQuery
        WHERE query=:query';
        if($_interactDef_id != null){
         $values['interactDef_id'] = $_interactDef_id;
         $sql .= ' AND interactDef_id=:interactDef_id';
     }
     return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
 }

 public static function byInteractDefId($_interactDef_id, $_enable = false) {
    $values = array(
        'interactDef_id' => $_interactDef_id
        );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM interactQuery
    WHERE interactDef_id=:interactDef_id';
    if ($_enable) {
        $sql .= ' AND enable=1';
    }
    $sql .= ' ORDER BY `query`';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
}

public static function byTypeAndLinkId($_type, $_link_id) {
    $values = array(
        'type' => $_type,
        'link_id' => $_link_id
        );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM interactQuery
    WHERE link_type=:type
    AND link_id=:link_id';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
}

public static function all() {
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM interactQuery
    ORDER BY id';
    return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
}

public static function removeByInteractDefId($_interactDef_id) {
    $values = array(
        'interactDef_id' => $_interactDef_id
        );
    $sql = 'DELETE FROM interactQuery
    WHERE interactDef_id=:interactDef_id';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
}

public static function recognize($_query) {
    $values = array(
        'query' => $_query,
        );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . ', MATCH query AGAINST (:query IN NATURAL LANGUAGE MODE) as score 
    FROM interactQuery 
    WHERE enable = 1
    GROUP BY id
    HAVING score > 1';
    $queries = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL,PDO::FETCH_CLASS, __CLASS__);
    $caracteres = array(
        'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', '@' => 'a',
        'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', '€' => 'e',
        'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Ö' => 'o', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
        'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'µ' => 'u',
        'Œ' => 'oe', 'œ' => 'oe',
        '$' => 's');
    $shortest = 999;
    $closest = null;
    $_query = strtolower(preg_replace('#[^A-Za-z0-9 \n\.\'=\*:]+#', '', strtr($_query, $caracteres)));
    foreach ($queries as $query) {
        $input = strtolower(preg_replace('#[^A-Za-z0-9 \n\.\'=\*:]+#', '', strtr($query->getQuery(), $caracteres)));
        preg_match_all("/#(.*?)#/", $input, $matches);
        foreach ($matches[1] as $match) {
            $input = str_replace('#' . $match . '#', '', $input);
        }
        $lev = levenshtein($_query, $input);
        if ($lev == 0) {
            $closest = $query;
            break;
        }
        if ($lev <= $shortest || $shortest < 0) {
            $closest = $query;
            $shortest = $lev;
        }

    }
    if($shortest > 10){
        log::add('interact','debug','Correspondance trop éloigné : '.$shortest);
        return null;
    }
    return $closest;
}

public static function whatDoYouKnow($_object = null) {
    $results = jeedom::whatDoYouKnow($_object);
    $reply = '';
    foreach ($results as $object) {
        $reply .= __('*** Je sais que pour ', __FILE__) . $object['name'] . " : \n";
        foreach ($object['eqLogic'] as $eqLogic) {
            foreach ($eqLogic['cmd'] as $cmd) {
                $reply.= $eqLogic['name'] . ' ' . $cmd['name'] . ' = ' . $cmd['value'] . ' ' . $cmd['unite'] . "\n";
            }
        }
    }
    return $reply;
}

public static function tryToReply($_query, $_parameters = array()) {
    $_parameters['dictation'] = $_query;
    if (isset($_parameters['profile'])) {
        $_parameters['profile'] = strtolower($_parameters['profile']);
    }
    $reply = '';
    $interactQuery = interactQuery::recognize($_query);
    if (is_object($interactQuery)) {
        $reply = $interactQuery->executeAndReply($_parameters);
    }
    if (trim($reply) == '' && (!isset($_parameters['emptyReply']) || $_parameters['emptyReply'] == 0)) {
        $reply = self::dontUnderstand($_parameters);
    }
    if(is_object($interactQuery)){
        log::add('interaction','debug','J\'ai reçu : '.$_query."\nJ'ai compris : ".$interactQuery->getQuery()."\nJ'ai répondu : ".$reply);
    }else{
        log::add('interaction','debug','J\'ai reçu : '.$_query."\nJe n'ai rien compris\nJ'ai répondu : ".$reply);
    }
    return ucfirst($reply);
}

public static function brainReply($_query, $_parameters) {
    global $PROFILE;
    $PROFILE = '';
    if (isset($_parameters['profile'])) {
        $PROFILE = $_parameters['profile'];
    }
    include_file('core', 'bot', 'config');
    global $BRAINREPLY;
    $shortest = 999;
    foreach ($BRAINREPLY as $word => $response) {
        $lev = levenshtein(strtolower($_query), strtolower($word));
        if ($lev == 0) {
            $closest = $word;
            $shortest = 0;
            break;
        }
        if ($lev <= $shortest || $shortest < 0) {
            $closest = $word;
            $shortest = $lev;
        }
    }
    if (isset($closest) && is_array($BRAINREPLY[$closest])) {
        $random = rand(0, count($BRAINREPLY[$closest]) - 1);
        return $BRAINREPLY[$closest][$random];
    }
    return '';
}

public static function dontUnderstand($_parameters) {
    $notUnderstood = array(
        __('Désolé je n\'ai pas compris', __FILE__),
        __('Désolé je n\'ai pas compris la demande', __FILE__),
        __('Désolé je ne comprends pas la demande', __FILE__),
        __('Je ne comprends pas', __FILE__),
        );
    if (isset($_parameters['profile'])) {
        $notUnderstood[] = __('Désolé ', __FILE__) . $_parameters['profile'] . __(' je n\'ai pas compris', __FILE__);
        $notUnderstood[] = __('Désolé ', __FILE__) . $_parameters['profile'] . __(' je n\'ai pas compris votre demande', __FILE__);
    }
    $random = rand(0, count($notUnderstood) - 1);
    return $notUnderstood[$random];
}

public static function replyOk() {
    $reply = array(
        __('C\'est fait', __FILE__),
        __('Ok', __FILE__),
        __('Voila, c\'est fait', __FILE__),
        __('Bien compris', __FILE__),
        );
    $random = rand(0, count($reply) - 1);
    return $reply[$random];
}

/*     * *********************Méthodes d'instance************************* */

public function save() {
    if ($this->getQuery() == '') {
        throw new Exception(__('La commande vocale ne peut pas être vide', __FILE__));
    }
    if ($this->getInteractDef_id() == '') {
        throw new Exception(__('SarahDef_id ne peut pas être vide', __FILE__));
    }
    if ($this->getLink_id() == '' && $this->getLink_type() != 'whatDoYouKnow') {
        throw new Exception(__('Cet ordre vocal n\'est associé à aucune commande : ', __FILE__) . $this->getQuery());
    }
    return DB::save($this);
}

public function remove() {
    return DB::remove($this);
}

public function executeAndReply($_parameters) {
    $interactDef = interactDef::byId($this->getInteractDef_id());
    if (!is_object($interactDef)) {
        return __('Inconsistance de la base de données', __FILE__);
    }
    if (isset($_parameters['profile']) && trim($interactDef->getPerson()) != '') {
        $person = strtolower($interactDef->getPerson());
        $person = explode('|', $person);
        if (!in_array($_parameters['profile'], $person)) {
            return __('Vous n\'êtes pas autorisé à exécuter cette action', __FILE__);
        }
    }
    if ($this->getLink_type() == 'whatDoYouKnow') {
        $object = object::byId($this->getLink_id());
        if (is_object($object)) {
            $reply = self::whatDoYouKnow($object);
            if (trim($reply) == '') {
                return __('Je ne sais rien sur ', __FILE__) . $object->getName();
            }
            return $reply;
        }
        return self::whatDoYouKnow();
    }
    if ($this->getLink_type() == 'scenario') {
        $scenario = scenario::byId($this->getLink_id());
        if (!is_object($scenario)) {
            return __('Impossible de trouver le scénario correspondant', __FILE__);
        }
        $interactDef = $this->getInteractDef();
        if (!is_object($interactDef)) {
            return __('Impossible de trouver la définition de l\'interaction', __FILE__);
        }
        $reply = $interactDef->selectReply();
        if (trim($reply) == '') {
            $reply = self::replyOk();
        }
        switch ($interactDef->getOptions('scenario_action')) {
            case 'start':
            $scenario->launch(false, __('Scenario exécuté sur interaction (S.A.R.A.H, SMS...)', __FILE__));
            return $reply;
            case 'stop':
            $scenario->stop();
            return $reply;
            case 'activate':
            $scenario->setIsActive(1);
            $scenario->save();
            return $reply;
            case 'deactivate':
            $scenario->setIsActive(0);
            $scenario->save();
            return $reply;
            default:
            return __('Aucune action n\'est définie dans l\'interaction sur le scénario : ', __FILE__) . $scenario->getHumanName();
        }
    }

    $reply = $interactDef->selectReply();
    $synonymes = array();
    if ($interactDef->getOptions('synonymes') != '') {
        foreach (explode('|', $interactDef->getOptions('synonymes')) as $value) {
            $values = explode('=', $value);
            $synonymes[strtolower($values[0])] = explode(',', $values[1]);
        }
    }
    $replace = array();
    $reply = scenarioExpression::setTags($reply);

    if ($this->getLink_type() == 'cmd') {
        $cmd = cmd::byId($this->getLink_id());
        if (!is_object($cmd)) {
            log::add('interact', 'error', __('Commande : ', __FILE__) . $this->getLink_id() . __(' introuvable veuillez renvoyer les listes des commandes', __FILE__));
            return __('Commande introuvable - vérifiez si elle existe toujours', __FILE__);
        }
        $replace['#commande#'] = $cmd->getName();
        if (isset($synonymes[strtolower($cmd->getName())])) {
            $replace['#commande#'] = $synonymes[strtolower($cmd->getName())][rand(0, count($synonymes[strtolower($cmd->getName())]) - 1)];
        }
        $replace['#objet#'] = '';
        $replace['#equipement#'] = '';
        $replace['#profile#'] = isset($_parameters['profile']) ? $_parameters['profile'] : '';
        $eqLogic = $cmd->getEqLogic();
        if (is_object($eqLogic)) {
            $replace['#equipement#'] = $eqLogic->getName();
            $object = $eqLogic->getObject();
            if (is_object($object)) {
                $replace['#objet#'] = $object->getName();
            }
        }

        $replace['#unite#'] = $cmd->getUnite();
        if ($cmd->getType() == 'action') {
            $options = null;
            $query = $this->getQuery();
            preg_match_all("/#(.*?)#/", $query, $matches);
            $matches = $matches[1];
            if (count($matches) > 0) {
                if (!isset($_parameters['dictation'])) {
                    return __('Erreur aucune phrase envoyée. Impossible de remplir les trous', __FILE__);
                }
                $dictation = $_parameters['dictation'];
                $options = array();
                $start = 0;
                $bitWords = array();
                foreach ($matches as $match) {
                    $bitWords[] = substr($query, $start, strpos($query, '#' . $match . '#') - $start);
                    $start = strpos($query, '#' . $match . '#') + strlen('#' . $match . '#');
                }
                if ($start < strlen($query)) {
                    $bitWords[] = substr($query, $start);
                }
                $i = 0;
                foreach ($matches as $match) {
                    if (isset($bitWords[$i])) {
                        $start = strpos($dictation, $bitWords[$i]);
                    } else {
                        $start = 0;
                    }
                    if (isset($bitWords[$i + 1])) {
                        $end = strpos($dictation, $bitWords[$i + 1]);
                        $options[$match] = trim(substr($dictation, $start + strlen($bitWords[$i]), $end - ($start + strlen($bitWords[$i]))));
                    } else {
                        $options[$match] = trim(substr($dictation, $start + strlen($bitWords[$i])));
                    }

                    $i++;
                }
            }
            try {
                if ($cmd->execCmd($options) === false) {
                    return __('Impossible d\'exécuter la commande', __FILE__);
                }
            } catch (Exception $exc) {
                return $exc->getMessage();
            }
            if ($options != null) {
                foreach ($options as $key => $value) {
                    $replace['#' . $key . '#'] = $value;
                }
            }
        }
        if ($cmd->getType() == 'info') {
            $value = $cmd->execCmd();
            if ($value === null) {
                return __('Impossible de récupérer la valeur de la commande', __FILE__);
            } else {
                $replace['#valeur#'] = $value;
                if ($cmd->getSubType() == 'binary' && $interactDef->getOptions('convertBinary') != '') {
                    $convertBinary = $interactDef->getOptions('convertBinary');
                    $convertBinary = explode('|', $convertBinary);
                    $replace['#valeur#'] = $convertBinary[$replace['#valeur#']];
                }
            }
        }
    }
    return scenarioExpression::setTags(str_replace(array_keys($replace), $replace, $reply));
}

public function getInteractDef() {
    return interactDef::byId($this->interactDef_id);
}

/*     * **********************Getteur Setteur*************************** */

public function getInteractDef_id() {
    return $this->interactDef_id;
}

public function setInteractDef_id($interactDef_id) {
    $this->interactDef_id = $interactDef_id;
}

public function getId() {
    return $this->id;
}

public function setId($id) {
    $this->id = $id;
}

public function getQuery() {
    return $this->query;
}

public function setQuery($query) {
    $this->query = $query;
}

public function getLink_type() {
    return $this->link_type;
}

public function setLink_type($link_type) {
    $this->link_type = $link_type;
}

public function getLink_id() {
    return $this->link_id;
}

public function setLink_id($link_id) {
    $this->link_id = $link_id;
}

public function getEnable() {
    return $this->enable;
}

public function setEnable($enable) {
    $this->enable = $enable;
}

}

?>
