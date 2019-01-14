<?php

namespace Jeedom\Core\Renderer;

class PHPRenderer implements Renderer
{
    const GLOBAL_NAMESPACE = '@jeedom';

    private $namespaces = [];

    public function __construct($path)
    {
        $this->namespaces[self::GLOBAL_NAMESPACE] = $path;
    }

    /**
     * @param $template
     * @param array $data
     *
     * @return string
     * @throws TemplateNotExistsException
     */
    public function render($template, array $data = [])
    {
        $templateFile = $this->getTemplateFile($template);

        ob_start();
        extract($data, EXTR_OVERWRITE);
        include $templateFile;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private function getTemplateFile($template)
    {
        $templateFile = str_replace('/', DIRECTORY_SEPARATOR, $this->resolveNamespace($template)).'.php';
        if (!file_exists($templateFile) || !is_readable($templateFile)) {
            throw new TemplateNotExistsException('Template '.$template. ' not exists. File '.$templateFile.' not exists.');
        }

        return $templateFile;
    }

    public function addNamespace($namespace, $templatePath)
    {
        $this->namespaces[$namespace] = $templatePath;
    }

    private function resolveNamespace($template)
    {
        $namespace = self::GLOBAL_NAMESPACE;
        if (0 === strpos($template, '@')) {
            $endNamespacePosition = strpos($template, '/') - 1;
            $namespace = substr($template, 1, $endNamespacePosition);
            $template = substr($template, $endNamespacePosition + 1);
        }

        return $this->namespaces[$namespace].'/'.$template;
    }
}
