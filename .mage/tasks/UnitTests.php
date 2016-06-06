<?php

namespace Task;

use Mage\Task\AbstractTask;

class UnitTests extends AbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Running tests';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->runCommandLocal('vendor/bin/phpunit')) {
            return false;
        }

        // This will test if the application is bootable (building the container)
        return $this->runCommandLocal('app/console contao:version');
    }
}
