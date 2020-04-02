<?php

declare(strict_types=1);

namespace Shapecode\Bundle\CronBundle\DependencyInjection;

use Shapecode\Bundle\CronBundle\Entity as BundleEntities;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class ShapecodeCronExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    /**
     * @inheritDoc
     */
    public function loadInternal(array $config, ContainerBuilder $container) : void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('shapecode_cron.results.auto_prune', $config['results']['auto_prune']);
        $container->setParameter('shapecode_cron.results.interval', $config['results']['interval']);
    }

    public function prepend(ContainerBuilder $container) : void
    {
        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'resolve_target_entities' => [
                    BundleEntities\CronJobInterface::class       => BundleEntities\CronJob::class,
                    BundleEntities\CronJobResultInterface::class => BundleEntities\CronJobResult::class,
                ],
            ],
        ]);
    }
}
