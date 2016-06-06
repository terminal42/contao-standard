<?php

namespace Task;

use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class MaintenanceUnlock extends SymfonyAbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Disabling maintenance mode';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->runCommandRemote($this->getAppPath() . ' lexik:maintenance:unlock')) {
            return false;
        }

        return true;
    }
}
