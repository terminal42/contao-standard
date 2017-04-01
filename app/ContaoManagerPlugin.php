<?php

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle;
use Terminal42\FolderpageBundle\Terminal42FolderpageBundle;
use Terminal42\LeadsBundle\Terminal42LeadsBundle;

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
            BundleConfig::create(Terminal42FolderpageBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
            BundleConfig::create(Terminal42LeadsBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
