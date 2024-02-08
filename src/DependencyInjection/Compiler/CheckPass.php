<?php

declare(strict_types=1);

namespace bitbirddev\TrustkeyWebhookBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class CheckPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
    }
}
