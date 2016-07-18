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
        $env = $this->getParameter('env', 'dev');

        $commands = [
            $this->getAppPath() . ' contao:install --env=' . $env,
            $this->getAppPath() . ' contao:symlink --env=' . $env,
            'test -f "system/config/localconfig.php" && ' . $this->getAppPath() . ' contao:automator purgePageCache --env=' . $env,
            'test -f "system/config/localconfig.php" && ' . $this->getAppPath() . ' contao:automator generateXmlFiles --env=' . $env,
        ];

        foreach ($commands as $command) {
            if (!$this->runCommand($command)) {
                return false;
            }
        }

        return true;
    }
}
