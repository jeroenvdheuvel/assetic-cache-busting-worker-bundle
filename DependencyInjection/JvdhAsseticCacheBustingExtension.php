<?php

namespace jvdh\AsseticCacheBustingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class JvdhAsseticCacheBustingExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['enabled']) {
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.yml');

            $container->setParameter('jvdh.assetic_cache_busting.separator', $config['separator']);
            $container->setParameter('jvdh.assetic_cache_busting.hash_length', $config['hash_length']);
            $container->setParameter('jvdh.assetic_cache_busting.hash_algorithm', $config['hash_algorithm']);

            $service = $container->getDefinition('jvdh.assetic_cache_busting');
            $service->addTag('assetic.factory_worker');
        }
    }
}
