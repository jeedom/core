
/**
 * Ensemble des variables et fonctions de configuration / défaut
 * @namespace jeedom.private
 */
var init = function(_param, _default) {
    return (typeof _param == 'number') ? _param : (typeof _param != 'boolean' || _param) && (_param !== false && _param || _default || '');
}


jeedom.private = {
    /**
     * Paramètres par défaut de toutes les fonctions de l'API
     * Ces valeurs sont merges avec les paramètres appelés à chaque appel de fonction
     * @example default_params = {
     *      async : true,         // Appel AJAX synchrone (deprecated) ou non
     *      type : 'POST',        // Transmission des données
     *      dataTye : 'json',     // Type de données échangées
     *      error : jeedom.private.fn_error, // Callback en cas d'erreur
     *      success : function (_data) {      // Callback en cas de succès
     *          return _data;
     *      },
     *      complete : function () {}        // Callback quoi qu'il se passe
     * };
     */
    default_params: {
        async: true,
        type: 'POST',
        dataType: 'json',
        pre_success: function(_data) {
            return _data;
        },
        success: function(_data) {
            console.log(_data);
        },
        post_success: function(_data) {
        },
        complete: function() {
        },
        error: function(_data) {
            // Erreur dans l'API ou mauvais retour AJAX (appel de ajax::error() côté PHP)
            console.log(_data);
        }
    },
    /**
     * Objet retourné quand tout s'est bien passé
     */
    API_end_successful: 'API\'s call went alright, AJAX is running or ended if {async : false} ! Doesn\'t mean it\'s going to work as expected... It depends on your parameters, none traitment has been made.',
    code: 42

};

/**
 * String to help user know what's going on
 */
var no_error_code = 'No error code has been sent.';
var no_result = '';
var code = 42;

/**
 * Fonction de conversion du retour AJAX en cas d'erreur en objet pour la fonction d'erreur
 */
jeedom.private.handleAjaxErrorAPI = function(_request, _status, _error) {
    if (_request.status && _request.status != '0') {
        if (_request.responseText) {
            return {type: 'AJAX', code: code, message: _request.responseText};
        } else {
            return {type: 'AJAX', code: code, message: _request.status + ' : ' + _error};
        }
    }
};


/**
 * Retourne les paramètres AJAX de l'API en fonction des paramètres choisis par l'utilisateur
 */
jeedom.private.getParamsAJAX = function(_params) {
    // cas particulier du type dans les paramètres
    var typeInData = false;

    // si type est dans les paramètres et est différent de POST ou GET
    if ($.inArray(_params.type, ['POST', 'GET']) === -1)
    {
        typeInData = true;
        _params.data = _params.data || {};
        _params._type = _params.type; // on stocke la donnée
        _params.type = 'POST'; // post par défaut
    }

    var paramsAJAX = {
        type: _params.type,
        dataType: _params.dataType,
        async: _params.async,
        global: _params.global,
        error: function(_request, _status, _error) {
            _params.error(jeedom.private.handleAjaxErrorAPI(_request, _status, _error));
        },
        success: function(data) {
            data = _params.pre_success(data);
            if (data.state != 'ok') {
                _params.error({
                    type: 'PHP',
                    message: data.result || 'Error - ' + no_result || '',
                    code: data.code || no_error_code || ''
                });
            }
            else {
                // On envoie les données à l'utilisateur, tout s'est bien passé
                // Il récupère l'objet qu'il a demandé directement
                var result = init(data.result, no_result);

                if (data.result === false) {
                    result = false;
                }

                _params.success(result);
            }
            _params.post_success(data);
        },
        complete: _params.complete,
        data: {}
    };

    if (typeInData) {
        paramsAJAX.data.type = _params._type;
    }

    return paramsAJAX; // return
};
/**
 * Fonction qui va checker si la valeur d'un paramètre vérifie une regexp
 * C'est récursif pour les arrays et les objets pris en value
 * DOIT ETRE ENCADRÉE D'UN TRY { } CATCH (e) {}
 * @param {Object} _params
 * @param _params.value Valeur du paramètre à tester
 * @param {Object} _params.regexp Regexp à vérifier
 * @param {string} [_params.name] Nom du paramètre à tester [optionnel]
 */

// tests en console :
// try { jeedom.private.checkParamsValue({value : [{test : 'check', test2 :'eeee'},{test : 'oefop', test2 : 'kfefe', test3 : 10}], regexp : /a|e|ch|1|zec/}); } catch(e) { console.log(e); }
jeedom.private.checkParamValue = function(_params) {
    try {
        checkParamsRequired(_params, ['value', 'regexp']);
    } catch (e) {
        throw {type: 'API', code: code, message: 'Une erreur est présente dans l\'API SARA JS. Les paramètres spécifiés dans checkParamValue ne sont pas complets. ' + e.message};
    }

    var value = _params.value;
    var regexp = _params.regexp;
    var name = _params.name || 'One parameter';

    if (typeof value == 'object') {
        // appel récursif pour les Array et les Objets
        for (var i in value) {
            checkParamValue({
                name: name,
                value: value[i],
                regexp: regexp
            });
        }
    }
    else {
        value += ''; // on convertie la valeur en string

        // pour faire un inArray, utiliser la regexp : /mot1|mot2|mot3|mot4/
        if (regexp.test(value) === false) {
            throw {type: 'API', code: code, message: name + ' isn\'t correct (doesn\'t match : ' + regexp.toString() + '). `' + value + '` received.'};
        }
    }
};



/** Fonction qui permet de vérifier que tous les paramètres obligatoires ont bien été renseignés dans l'objet params
 * Note : chaque fonction doit appeler cette fonction au préalable après avoir créé un string[] composé des paramètres requis.
 * @return {Object} ret Contient les résultats du check
 * @return {boolean} ret.result Nous renseigne si les paramètres requis ont bien été remplis
 * @return {Object[]} ret.missing Ensemble des options qui n'ont pas été renseignées, optionnelles ou non
 * @return {string} ret.missing.name Nom d'un paramètre manquant
 * @return {boolean} ret.missing.optional Caractère optionnel ou non du paramètre manquant
 * @return {number} ret.missing.group Groupe associé au paramètre (0 pour les paramètres obligatoires et n pour les paramètres optionnels, ce numéro est identique pour les membres d'un même groupe, il faut qu'au moins l'un d'entre eux soit précisé pour que la fonction fonctionne)
 * @return {string} ret.missing.toString Renvoie un paramètre manquant sous forme de string pour l'affichage
 */
jeedom.private.checkParamsRequired = function(_params, _paramsRequired) {
    var missings = Array();
    var group = Array();
    var missingAtLeastOneParam = false;
    var optionalGroupNumber = 0;

    for (var key in _paramsRequired)
    {
        if (typeof _paramsRequired[key] === 'object')
        {
            optionalGroupNumber++;

            // il y a plusieurs clés, il faut qu'au moins l'une d'entre elles soit présente
            var ok = false;
            for (var key2 in _paramsRequired[key])
            {
                if (_params.hasOwnProperty(_paramsRequired[key][key2]))
                {
                    ok = true;
                    // pas de break, on veut savoir quels paramètres sont présents et lesquels ne le sont pas.
                }
                else
                {
                    missings.push({
                        name: _paramsRequired[key][key2],
                        optional: true,
                        group: {
                            id: optionalGroupNumber
                        }
                    });
                }
            }

            // on indique si le groupe a été check ou pas
            group[optionalGroupNumber] = {
                checked: ok
            };

            // de manière plus globale, on indique s'il manque un paramètre obligatoire ou pas
            if (!ok)
            {
                missingAtLeastOneParam = true;
            }
        }
        else if (!_params.hasOwnProperty(_paramsRequired[key]))
        {
            missings.push({
                name: _paramsRequired[key],
                optional: false,
                group: {
                    id: 0,
                    checked: false
                }
            });
            missingAtLeastOneParam = true;
        }
    }

    if (missingAtLeastOneParam) {
        var tostring = 'Parameters missing : ';
        for (var i in missings) {
            var miss = missings[i];
            tostring += miss.name + ' ';

            // dans le cas des paramètres optionnels, on rajoute une information pour savoir si le groupe d'options (optionnels donc) a été rempli (via une autre option non manquante donc) ou non
            var checkedstring = miss.optional && (group[miss.group.id].checked) ? 'yes' : 'no' || '';

            tostring += (miss.optional) ? '[optional, group=' + miss.group.id + ' checked=' + checkedstring + ']' : '[needed]';
            tostring += ', ';
        }

        // on enlève la virgule et l'espace en trop
        tostring = tostring.substring(0, tostring.length - 2);
        throw {
            type: 'API',
            code: code,
            message: tostring
        };
    }
    return;
};

/**
 * Check global
 * À impérativement encadrer de try {} catch () {}
 */
jeedom.private.checkAndGetParams = function(_params, _paramsSpecifics, _paramsRequired) {
    // throw une exception en cas d'erreur (à attraper plus haut)
    jeedom.private.checkParamsRequired(_params, _paramsRequired || []);

    // tout est ok, on merge avec les paramètres par défaut + les spécifiques à la fonction
    var params = $.extend({}, jeedom.private.default_params, _paramsSpecifics, _params || {});

    // on json_encode tous les objets contenus dans les params
    for (var attr in params) {
        var param = params[attr];
        params[attr] = (typeof param == 'object') ? json_encode(param) : param;
    }

    var paramsAJAX = jeedom.private.getParamsAJAX(params);

    return {
        params: params,
        paramsAJAX: paramsAJAX
    };
};

/**
 * Fonction générique qui permet de checker les valeurs des paramètres
 */
jeedom.private.checkParamsValue = function(_params) {
    if (Object.prototype.toString.call(_params) == '[object Object]') {
        jeedom.private.checkParamValue(_params);
    } else {
        for (var i in _params) {
            jeedom.private.checkParamValue(_params[i]);
        }
    }
};