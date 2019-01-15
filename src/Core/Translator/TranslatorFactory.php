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

        $plugins = \plugin::listPlugin(true, false, false, true);

        $symfonyTranslator = new SymfonyTranslator(
            $language,
            null,
            dirname(__DIR__, 3).'/var/cache/translations',
            getenv('DEBUG')
        );

        $symfonyTranslator->addLoader('array', new ArrayLoader());

        $languageFile = '/core/i18n/' . $language . '.json';
        self::addFile($symfonyTranslator, $language, dirname(__DIR__, 3) . $languageFile);
        foreach ($plugins as $plugin) {
            self::addFile($symfonyTranslator, $language, dirname(__DIR__, 3) . '/plugins/' . $plugin . $languageFile);
        }

        if (!isset(self::$translators[$language])) {
            self::$translators[$language] = new SymfonyTranslatorAdaptor($symfonyTranslator);
        }

        return self::$translators[$language];
    }

    /**
     * @param SymfonyTranslator $symfonyTranslator
     * @param $language
     * @param string $fileName
     *
     * @return void
     */
    private static function addFile(SymfonyTranslator $symfonyTranslator, $language, $fileName)
    {
        if (!file_exists($fileName) || !is_readable($fileName)) {
            return;
        }
        $file = json_decode(file_get_contents($fileName), true);

        foreach ($file as $domain => $resource) {
            $symfonyTranslator->addResource('array', $resource, $language, $domain);
        }
    }
}
