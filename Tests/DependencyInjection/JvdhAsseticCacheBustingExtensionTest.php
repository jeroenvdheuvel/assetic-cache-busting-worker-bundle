<?php

namespace jvdh\AsseticCacheBustingBundle\Tests\DependencyInjection;

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use jvdh\AsseticCacheBustingBundle\DependencyInjection\JvdhAsseticCacheBustingExtension;

class JvdhAsseticCacheBustingExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
    }

    public function testLoadExtension_withoutConfig_shouldDisableCacheBustingWorker()
    {
        $this->loadExtension(array());

        $this->assertEmpty($this->findAsseticFactoryWorkerTaggedServices());
    }

    public function testLoadExtension_withConfigOnlyEnabled_shouldEnableCacheBustingWorkerWithDefaultOptions()
    {
        $this->loadExtension(array('enabled' => true));

        $this->assertAsseticCacheBustingServiceIsEnabled('jvdh.assetic_cache_busting');
        $this->assertAsseticCacheBustingParameters('-', 8, 'sha1');
    }


    public function testLoadExtension_withConfigCompletelyProvided_shouldEnableCacheBustingWorkerWithAllOptions()
    {
        $config = array('enabled' => true, 'separator' => '+', 'hash_length' => 2, 'hash_algorithm' => 'md5', 'enable_cached_worker' => true);
        $this->loadExtension($config);

        $this->assertAsseticCacheBustingServiceIsEnabled('jvdh.assetic_cached_worker');
        $this->assertAsseticCacheBustingParameters($config['separator'], $config['hash_length'], $config['hash_algorithm']);
    }

    /**
     * @param array $config
     */
    private function loadExtension(array $config)
    {
        $extension = new JvdhAsseticCacheBustingExtension();
        $extension->load(array($config), $this->container);
    }

    /**
     * @return array
     */
    private function findAsseticFactoryWorkerTaggedServices()
    {
        return $this->container->findTaggedServiceIds('assetic.factory_worker');
    }

    /**
     * @param string $serviceId
     */
    private function assertAsseticCacheBustingServiceIsEnabled($serviceId)
    {
        $services = $this->findAsseticFactoryWorkerTaggedServices();

        $this->assertCount(1, $services);
        $this->assertSame($serviceId, key($services));
    }

    /**
     * @param string $separator
     * @param int $hashLength
     * @param string $hashAlgorithm
     */
    private function assertAsseticCacheBustingParameters($separator, $hashLength, $hashAlgorithm)
    {
        $this->assertSame($separator, $this->container->getParameter('jvdh.assetic_cache_busting.separator'));
        $this->assertSame($hashLength, $this->container->getParameter('jvdh.assetic_cache_busting.hash_length'));
        $this->assertSame($hashAlgorithm, $this->container->getParameter('jvdh.assetic_cache_busting.hash_algorithm'));
    }
}
