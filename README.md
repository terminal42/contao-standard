Contao edition for terminal42 gmbh
==================================

Welcome to terminal42's Contao edition, a fully-functional Contao 4 application
that you can use as the skeleton for your new applications.


What's inside?
--------------

Our Contao edition is configured with the following defaults:

  * Doctrine DBAL & ORM;
  * Doctrine ORM file caching;
  * Doctrine Migrations;
  * Annotations for everything;
  * Magallanes for deployment.

It comes pre-configured with the following Symfony bundles:

  * **FrameworkBundle** - The core Symfony framework bundle
  * **SecurityBundle** - Integrates Symfony's security component
  * **TwigBundle** - Adds support for the Twig templating engine
  * **MonologBundle** - Adds support for Monolog, a logging library
  * **SwiftmailerBundle** - Adds support for Swiftmailer
  * **DoctrineBundle** - Adds support for Doctrine DBAL & ORM
  * **DoctrineCacheBundle** - Adds support for Doctrine cache
  * **DoctrineMigrationsBundle** - Adds support for Doctrine migrations
  * **LexikMaintenanceBundle** - Allows to toggle maintenance mode
  * **SensioFrameworkExtraBundle** - Adds various annotation capabilities
  * **DebugBundle** (in dev/test env) - Adds the `dump()` function
  * **WebProfilerBundle** (in dev/test env) - Adds the web debug toolbar
  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

It also comes pre-configured with the following Contao bundles:

  * **ContaoCoreBundle** - The Contao core bundle
  * **ContaoInstallationBundle** - The Contao installation bundle
  * **Terminal42 Notification Center** - To send notifications (e.g. email on form submit)  
  * **Terminal42LeadsBundle** - To store form submissions
  * **Terminal42FolderpageBundle** - To group pages in the page tree


Building your application:
--------------------------

Our Contao edition contains some useful defaults we came up with
in our Contao 4 projects.

  * Deployment is pre-configured using [Magallanes][mage]. Adjust the
    environments in /.mage/config/environments, enter your 
    server host and path name. Make sure to have composer.phar on
    your server as specified in /.mage/config/general.yml

  * Place your application classes in src/AppBundle as per 
    default Symfony best practices.
  
  * Place your style sheets in /web/layout/styles, preferrably
    using SCSS includes in the main app.scss. There's a predefined
    deployment task which will build the optimized CSS file.
  
  * Place your javascript in /web/layout/scripts. There's a predefined
    deployment task which will build an optimized and minified JS file.
    Additional scripts need to be added to the task in 
    /.mage/tasks/BuildScripts.php



[mage]: http://magephp.com
