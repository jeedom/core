<?php

namespace Jeedom\Core\Translator;

use Jeedom\Core\Configuration\ConfigurationFactory;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class TranslatorFactory
{
    /**
     * @var array
     */
    private static $translators = [];

    /**
     * @param null $language
     *
     * @return Translator
     */
    public static function build($language = null)
    {
        $configuration = ConfigurationFactory::build('core');
        if (null === $language) {
            $language = $configuration->get('language', 'fr_FR');
        }

        $symfonyTranslator = new SymfonyTranslator($language);
        $file = json_decode(file_get_contents(dirname(__DIR__, 3).'/core/i18n/' . $language . '.json'), true);
        $symfonyTranslator->addLoader('array', new ArrayLoader());

        foreach ($file as $domain => $resource) {
            $symfonyTranslator->addResource('array', $resource, $language, $domain);
        }

        if (!isset(self::$translators[$language])) {
            self::$translators[$language] = new SymfonyTranslatorAdaptor($symfonyTranslator);
        }

        return self::$translators[$language];
    }
}
