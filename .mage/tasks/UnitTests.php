<?php

namespace Task;

use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class UnitTests extends SymfonyAbstractTask
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
        // Enable to run unit tests
        //if (!$this->runCommandLocal('vendor/bin/phpunit')) {
        //    return false;
        //}

        // This will test if the application is bootable (building the container)
        return $this->runCommandLocal($this->getAppPath() . ' contao:version');
    }
}
