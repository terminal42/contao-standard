<?php

namespace Task;

use Mage\Task\AbstractTask;

class CleanReleases extends AbstractTask
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Removing old releases';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->getConfig()->release('enabled', false) !== true) {
            return false;
        }

        $releasesDirectory = $this->getConfig()->release('directory', 'releases');
        $releasesDirectory = rtrim($this->getConfig()->deployment('to'), '/') . '/' . $releasesDirectory;

        $maxReleases = $this->getParameter('max', 5);
        if (!$maxReleases) {
            return false;
        }

        $releasesList = '';
        $countReleasesFetch = $this->runCommandRemote('ls -1 ' . $releasesDirectory, $releasesList);
        $releasesList = trim($releasesList);

        if (!$countReleasesFetch || $releasesList == '') {
            return false;
        }

        $releasesList = explode(PHP_EOL, $releasesList);
        if (count($releasesList) > $maxReleases) {
            $releasesToDelete = array_diff($releasesList, array($this->getConfig()->getReleaseId()));
            sort($releasesToDelete);
            $releasesToDeleteCount = count($releasesToDelete) - $maxReleases;
            $releasesToDelete = array_slice($releasesToDelete, 0, $releasesToDeleteCount + 1);

            foreach ($releasesToDelete as $releaseIdToDelete) {
                $directoryToDelete = $releasesDirectory . '/' . $releaseIdToDelete;
                if ('/' !== $directoryToDelete) {
                    $command = 'rm -rf ' . $directoryToDelete;
                    $this->runCommandRemote($command);
                }
            }
        }

        return true;
    }
}
