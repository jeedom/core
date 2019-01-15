<?php

namespace Jeedom\Core\Translator;

interface Translator
{
    /**
     * @param string $content
     * @param string $domain
     *
     * @return string
     */
    public function translate($content, $domain);

    /**
     * @param string $content
     * @param string $domain
     *
     * @return string
     */
    public function exec($content, $domain);

    /**
     * @param string $language
     *
     * @return void
     */
    public function setLanguage($language);

    /**
     * @return string
     */
    public function getLanguage();
}
