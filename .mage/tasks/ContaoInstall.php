<?php

namespace Task;

use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class ContaoInstall extends SymfonyAbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Installing Contao environment';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $commands = [
            $this->getAppPath() . ' contao:install',
            $this->getAppPath() . ' contao:symlink',
            'test -f "system/config/localconfig.php" && ' . $this->getAppPath() . ' contao:automator purgePageCache',
            'test -f "system/config/localconfig.php" && ' . $this->getAppPath() . ' contao:automator generateXmlFiles',
        ];

        foreach ($commands as $command) {
            if (!$this->runCommand($command)) {
                return false;
            }
        }

        return true;
    }
}
