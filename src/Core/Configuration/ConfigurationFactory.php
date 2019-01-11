<?php

namespace Jeedom\Core\Configuration;

use Jeedom\Core\Configuration\Event\Configured;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ConfigurationFactory
{
    private static $defaultConfigurations = [];

    private static $configurations = [];

    private static $eventDispatcher;

    public static function build($plugin)
    {
        if (isset(self::$configurations[$plugin])) {
            return self::$configurations[$plugin];
        }

        if (!isset(self::$eventDispatcher)) {
            self::$eventDispatcher = new EventDispatcher();
            self::$eventDispatcher->addListener('preConfig', [self::class, 'preConfig']);
            self::$eventDispatcher->addListener('postConfig', [self::class, 'postConfig']);
        }

        $configuration = self::getDefaultConfiguration($plugin);
        $configuration->addPostConfiguration('test' === getenv('ENV')
            ? new InMemoryConfiguration()
            : new SQLDatabaseConfiguration($plugin)
        );

        $configuration = new EventDispatcherConfigurationDecorator(
            $plugin,
            self::$eventDispatcher, new InMemoryCacheConfigurationDecorator(
                $configuration
            )
        );

        if (getenv('DEBUG')) {
            $configuration = new LogConfigurationDecorator($configuration);
        }

        self::$configurations[$plugin] = $configuration;

        return self::$configurations[$plugin];
    }

    /**
     * @param string $plugin
     *
     * @return ChainConfiguration
     */
    public static function getDefaultConfiguration($plugin)
    {
        if (isset(self::$defaultConfigurations[$plugin])) {
            return self::$defaultConfigurations[$plugin];
        }

        $iniFiles = [];
        if ('core' === $plugin) {
            $iniFiles[] = ['core', dirname(__DIR__, 3) . '/data/custom/custom.config.ini'];
            $iniFiles[] = ['core', dirname(__DIR__, 3) . '/core/config/default.config.ini'];
        } else {
            $iniFiles[] = [$plugin, dirname(__DIR__, 3) . '/data/custom/custom.config.ini'];
            $iniFiles[] = [$plugin, dirname(__DIR__, 3) . '/plugins/' . $plugin . '/core/config/' . $plugin . '.config.ini'];
        }

        $configuration = [];
        foreach ($iniFiles as list($section, $filename)) {
            try {
                $configuration[] = new ReadOnlyIniFileConfiguration($filename, $section);
            } catch (\Exception $e) {
                echo $filename . ' not readable'.PHP_EOL;
            }
        }

        self::$defaultConfigurations[$plugin] = new ChainConfiguration($configuration);

        return self::$defaultConfigurations[$plugin];
    }

    public static function preConfig(Configured $event)
    {
        $key = $event->getKey();
        $plugin = $event->getPlugin();

        $class = $plugin === 'core' ? \config::class : '\\' . $plugin;

        $function = 'preConfig_' . str_replace(['::', ':'], '_', $key);
        if (method_exists($class, $function)) {
            $value = $event->getValue();
            $value = is_object($value) || is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $value = $class::$function($value);

            if (!function_exists('is_json')) {
                include_once dirname(__DIR__, 3) . '/core/php/utils.inc.php';
            }
            $event->setValue(is_json($value, $value));
        }
    }

    public static function postConfig(Configured $event)
    {
        $key = $event->getKey();
        $plugin = $event->getPlugin();

        $class = $plugin === 'core' ? \config::class : '\\' . $plugin;

        $function = 'postConfig_' . str_replace(['::', ':'], '_', $key);
        if (method_exists($class, $function)) {
            $value = $event->getValue();
            $value = is_object($value) || is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            $class::$function($value);
        }
    }
}
