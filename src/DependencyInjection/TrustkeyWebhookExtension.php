<?php

declare(strict_types=1);

namespace bitbirddev\TrustkeyWebhookBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;

final class TrustkeyWebhookExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->prependExtensionConfig('framework', []);
    }

    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $yamlLoader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $yamlLoader->load('services.yaml');
    }
}
