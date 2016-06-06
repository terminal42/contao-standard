<?php

namespace Task;

use Mage\Task\AbstractTask;

class BuildStyles extends AbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Compiling style sheets';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->runCommandLocal('/usr/local/bin/scss --style compressed --stop-on-error --sourcemap=none --no-cache --update web/layout/styles/app.scss:web/layout/app.css')) {
            return false;
        }

        $file = file_get_contents('web/layout/app.css');

        if (false === $file) {
            return false;
        }

        $file = str_replace('../', '', $file);

        return file_put_contents('web/layout/app.css', $file) !== false;
    }
}
