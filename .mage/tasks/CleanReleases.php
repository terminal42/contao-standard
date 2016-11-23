<?php

namespace Task;

use Mage\Console;
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

        $maxReleases = $this->getParameter('max', 5);
        if (!$maxReleases) {
            return false;
        }

        $hosts = $this->getConfig()->getHosts();

        if (0 === count($hosts)) {
            Console::output('<light_purple>Warning!</light_purple> <bold>No hosts defined, skipping deployment tasks.</bold>', 1, 3);

            return false;
        }

        Console::output('', 1, 1);

        foreach ($hosts as $hostKey => $host) {

            // Check if Host has specific configuration
            $hostConfig = null;
            if (is_array($host)) {
                $hostConfig = $host;
                $host       = $hostKey;
            }

            // Set Host and Host Specific Config
            $this->getConfig()->setHost($host);
            $this->getConfig()->setHostConfig($hostConfig);

            if (false === $this->cleanHost($maxReleases)) {
                return false;
            }
        }

        $this->getConfig()->setHostConfig(null);

        Console::output('all ', 3, 0);

        return true;
    }

    private function cleanHost($maxReleases)
    {
        $releasesDirectory = $this->getConfig()->release('directory', 'releases');
        $releasesDirectory = rtrim($this->getConfig()->deployment('to'), '/') . '/' . $releasesDirectory;

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
            $first = true;

            Console::output(sprintf('from <bold>%s</bold>: ', $this->getConfig()->getHost()), 3, 0);

            foreach ($releasesToDelete as $releaseIdToDelete) {
                if (!$first) {
                    Console::output(', ', 0, 0);
                }

                Console::output('<purple>'.$releaseIdToDelete.'</purple>', 0, 0);

                $directoryToDelete = $releasesDirectory . '/' . $releaseIdToDelete;
                if ('/' !== $directoryToDelete) {
                    $command = 'rm -rf ' . $directoryToDelete;
                    $this->runCommandRemote($command);
                }

                $first = false;
            }

            Console::output('', 0, 1);
        }

        return true;
    }
}
