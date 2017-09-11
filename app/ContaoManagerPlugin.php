<?php

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle;

class ContaoManagerPlugin implements BundlePluginInterface
{
    /**
     * Gets a list of autoload configurations for this bundle.
     *
     * @param ParserInterface $parser
     *
     * @return ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(AcceleratorCacheBundle::class),
            BundleConfig::create(DoctrineMigrationsBundle::class),
        ];
    }
}
