<?php

namespace Task;

use Mage\Task\AbstractTask;

class Gulp extends AbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Running Gulp';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $prod = $this->getParameter('env', 'dev') === 'prod';

        if (!$this->runCommandLocal('gulp'.($prod ? ' --prod' : ''))) {
            return false;
        }

        return true;
    }
}
