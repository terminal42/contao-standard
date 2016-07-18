<?php

namespace Task;

use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class MaintenanceLock extends SymfonyAbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Enabling maintenance mode';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $env = $this->getParameter('env', 'dev');

        if (!$this->runCommandRemote($this->getAppPath() . ' lexik:maintenance:lock --env=' . $env)) {
            return false;
        }

        return true;
    }
}
