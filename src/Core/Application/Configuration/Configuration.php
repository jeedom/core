<?php

namespace Jeedom\Core\Application\Configuration;

interface Configuration
{
    /**
     * Retourne la valeur liée à la clé
     * Si la clé n'existe pas retourne la valeur de $default
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Définie une valeur liée à une clé
     * Si la clé existe déjà elle sera écrasée
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws ReadOnlyConfigurationException
     *
     * @return Configuration
     */
    public function set($key, $value);

    /**
     * Supprime une clé et sa valeur liée
     *
     * @param string $key
     *
     * @throws ReadOnlyConfigurationException
     *
     * @return Configuration
     */
    public function remove($key);

    /**
     * Retourne un tableau clé => valeur des clés demandées
     * Si un clé n'existe pas :
     *   - Si default est un tableau, il retournera la valeur correspondant à la clé associée de default, null sinon
     *   - Sinon il retournera default
     *
     * @param string[] $keys
     *
     * @param mixed[]|mixed|null $default
     *
     * @return mixed[]
     */
    public function multiGet(array $keys, $default = null);

    /**
     * Retourne les clés/valeur correspondant au pattern
     *
     * @param string $pattern
     *
     * @return mixed[]
     */
    public function search($pattern);
}
