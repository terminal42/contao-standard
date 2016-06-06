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
            'ln -s ../images web',
            $this->getAppPath() . ' contao:automator purgePageCache',
            $this->getAppPath() . ' contao:automator generateXmlFiles',
            $this->getAppPath() . ' contao:automator rotateLogs',
        ];

        foreach ($commands as $command) {
            if (!$this->runCommand($command)) {
                return false;
            }
        }

        return true;
    }
}
