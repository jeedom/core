<?php

namespace Jeedom\Core\Translator;

class SymfonyTranslatorAdaptor implements Translator
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * SymfonyTranslator constructor.
     *
     * @param \Symfony\Component\Translation\TranslatorInterface $symfonyTranslator
     *
     */
    public function __construct(\Symfony\Component\Translation\TranslatorInterface $symfonyTranslator)
    {
        $this->translator = $symfonyTranslator;
    }

    /**
     * @param $content
     * @param $domain
     *
     * @return string
     */
    public function translate($content, $domain)
    {
        return $this->translator->trans($content, [], $domain);
    }

    /**
     * @param $content
     * @param $domain
     *
     * @return string
     */
    public function exec($content, $domain)
    {
        if ($content === '' || $domain === '') {
            return '';
        }

        if (0 === strpos($domain, '/')) {
            if (strpos($domain, 'plugins') !== false) {
                $domain = substr($domain, strpos($domain, 'plugins'));
            } elseif (strpos($domain, 'core') !== false) {
                $domain = substr($domain, strpos($domain, 'core'));
            } elseif (strpos($domain, 'install') !== false) {
                $domain = substr($domain, strpos($domain, 'install'));
            }
        }

        $replace = [];
        preg_match_all('/{{(.*?)}}/s', $content, $matches);
        foreach ($matches[1] as $text) {
            $replace['{{' . $text . '}}'] = $this->translate($text, $domain);
        }

        return str_replace(array_keys($replace), $replace, $content);
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->translator->setLocale($language);
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->translator->getLocale();
    }
}
