<?php

namespace Task;

use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class DoctrineMigrate extends SymfonyAbstractTask
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Symfony v2 - Migrate doctrine entities';
    }

    /**
     * Migrates Doctrine entities
     *
     * @see \Mage\Task\AbstractTask::run()
     */
    public function run()
    {
        $env = $this->getParameter('env', 'dev');

        $command = $this->getAppPath() . ' doctrine:migrations:migrate -n --env=' . $env . ' --allow-no-migration';

        return $this->runCommand($command);
    }
}
