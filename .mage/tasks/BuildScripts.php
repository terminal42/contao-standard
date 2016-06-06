<?php

namespace Task;

use Mage\Task\AbstractTask;

class BuildScripts extends AbstractTask
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Compiling javascripts';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->runCommandLocal(
'/usr/local/bin/uglifyjs \
assets/respimage/js/respimage.js \
web/layout/scripts/app.js \
--compress \
-o web/layout/app.js'
        );
    }
}
