<?php

namespace Task;

use Mage\Task\BuiltIn\Composer\ComposerAbstractTask;

class ComposerUpdate extends ComposerAbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Composer self-update';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->runCommand($this->getComposerCmd() . ' self-update');
    }
}
