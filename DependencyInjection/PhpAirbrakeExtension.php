<?php

namespace Nodrew\Bundle\PhpAirbrakeBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class PhpAirbrakeExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $config = $this->mergeConfigs($configs);

        if (empty($config['api_key'])) {
            return;
        }

        $container->setParameter('php_airbrake.api_key', $config['api_key']);
        $container->setParameter('php_airbrake.api_endpoint', isset($config['api_endpoint']) ? $config['api_endpoint'] : null);
        $container->setParameter('php_airbrake.queue', isset($config['queue']) ? $config['queue'] : null);
        $container->setParameter('php_airbrake.email_to', isset($config['email_to']) ? $config['email_to'] : null);
        $container->setParameter('php_airbrake.disabled', isset($config['disabled']) ? $config['disabled'] : false);
    }

    /**
     * Merges the given configurations
     *
     * @param  array $configs An array of configurations
     * @return array The merged configuration
     */
    public function mergeConfigs(array $configs)
    {
        $merged = array();

        foreach ($configs as $config) {
            foreach ($config as $key => $value) {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * @return string
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return 'http://www.abstractcodification.com/schema/dic/php_airbrake_bundle';
    }
}
