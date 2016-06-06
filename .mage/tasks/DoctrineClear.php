<?php

namespace Task;

use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class DoctrineClear extends SymfonyAbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Clearing Doctrine cache';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $env = $this->getParameter('env', 'dev');

        if (!$this->runCommandRemote($this->getAppPath() . ' doctrine:cache:clear-metadata --flush --env=' . $env)) {
            return false;
        }

        if (!$this->runCommandRemote($this->getAppPath() . ' doctrine:cache:clear-query --flush --env=' . $env)) {
            return false;
        }

        if (!$this->runCommandRemote($this->getAppPath() . ' doctrine:cache:clear-result --flush --env=' . $env)) {
            return false;
        }

        return true;
    }
}
