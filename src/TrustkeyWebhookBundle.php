<?php

declare(strict_types=1);

namespace bitbirddev\TrustkeyWebhookBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use bitbirddev\TrustkeyWebhookBundle\DependencyInjection\Compiler\CheckPass;
use bitbirddev\TrustkeyWebhookBundle\DependencyInjection\TrustkeyWebhookExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use function dirname;

final class TrustkeyWebhookBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TrustkeyWebhookExtension();
    }

    // public function build(ContainerBuilder $container): void
    // {
    //     $container->addCompilerPass(new CheckPass());
    // }
}
