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

global $BRAINREPLY;
global $PROFILE;

$BRAINREPLY = array(
    'coucou' => array('Salut', 'Hello', 'Hey, comment ca va ?', 'Salut comment ca va ?'),
    'ca va' => array('Bien et toi', 'Bien', 'Ca roule'),
    'bien' => array('Cool'),
    'merci' => array('De rien'),
);

$synonyme = array(
    'coucou' => array('Salut', 'Bonjour','Salut!','Salut !'),
    'ca va' => array('Bien et toi'),
);

if (isset($PROFILE) && $PROFILE != '') {
    $profileReply = array(
        'coucou' => array('Salut ' . $PROFILE.', tu vas bien ?'),
        'ca va' => array('Bien et toi ' . $PROFILE),
        'merci' => array('De rien ' . $PROFILE),
    );

    foreach ($profileReply as $query => $response) {
        if (isset($BRAINREPLY[$query])) {
            $BRAINREPLY[$query] = array_merge($BRAINREPLY[$query], $response);
        } else {
            $BRAINREPLY[$query] = $response;
        }
    }
}

foreach ($BRAINREPLY as $reply => $response) {
    if (isset($synonyme[$reply])) {
        foreach ($synonyme[$reply] as $synonyme) {
            if (isset($BRAINREPLY[$synonyme])) {
                $BRAINREPLY[$synonyme] = array_merge($BRAINREPLY[$synonyme], $response);
            } else {
                $BRAINREPLY[$synonyme] = $response;
            }
        }
    }
}
