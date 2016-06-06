<?php

namespace Task;

use Mage\Task\AbstractTask;

class DevController extends AbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Enabling development entry point';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->runCommandRemote("mv web/app_dev.php web/app.php");

        return true;
    }
}
