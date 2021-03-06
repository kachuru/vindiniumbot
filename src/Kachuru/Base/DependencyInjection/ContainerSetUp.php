<?php

namespace Kachuru\Base\DependencyInjection;

use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\GlobFileLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;


class ContainerSetUp
{
    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public static function bootstrapContainer(array $additionalConfig = [])
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new CommandPass('vindinium-bot.console'));

        $locator = new FileLocator(__DIR__.'/../../../../config');
        $resolver = new LoaderResolver(array(
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new GlobFileLoader($locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ));

        $loader = new DelegatingLoader($resolver);

        $loader->load('parameters'.self::CONFIG_EXTS, 'glob');
        $loader->load('packages/*'.self::CONFIG_EXTS, 'glob');
        $loader->load('services'.self::CONFIG_EXTS, 'glob');
        foreach ($additionalConfig as $config) {
            $loader->load($config);
        }

        $container->compile();

        return $container;
    }
}
