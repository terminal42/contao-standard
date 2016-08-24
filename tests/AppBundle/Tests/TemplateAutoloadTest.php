<?php

namespace AppBundle\Tests\Templating;

use Contao\TemplateLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class TemplateAutoloadTest extends \PHPUnit_Framework_TestCase
{
    public function testTemplatesAreRegistered()
    {
        $root = dirname(dirname(dirname(__DIR__))) . '/';

        include_once $root . 'src/AppBundle/Resources/contao/config/autoload.php';
        include_once $root . 'app/Resources/contao/config/autoload.php';

        $registered = TemplateLoader::getFiles();

        $finder = Finder::create()
            ->files()
            ->ignoreDotFiles(true)
            ->name('*.html5')
            ->in(
                [
                    $root . 'src/AppBundle/Resources/contao/templates',
                    $root . 'app/Resources/contao/templates'
                ]
            )
        ;

        $found = iterator_to_array($finder);

        /** @var SplFileInfo $file */
        foreach ($found as $file) {
            $basename = basename($file->getFilename(), '.html5');

            static::assertArrayHasKey($basename, $registered);
            static::assertEquals(
                str_replace($root, '', dirname($file->getPathname())),
                $registered[$basename]
            );
        }

        static::assertCount(count($registered), $found);
    }
}
