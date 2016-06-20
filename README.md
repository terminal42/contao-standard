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


Building your application
-------------------------

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


Setup & initial deployment
--------------------------

Before you can initially deploy your application, make sure to correctly
configure the hosting.

1. Point your VirtualHost directory to `/%vhost_root%/current/web/`

2. Create the following folders in `/%vhost_root%/shared/`:
    - app/logs
    - app/config
    - files
     
3. Manually upload `app/config/parameters.yml.dist` to 
   `/%vhost_root%/shared/app/config/parameters.yml` and adjust the configuration
   (e.g. enter database credentials).

4. Make sure to delete the `current/web` folder on your server if it was
   automatically created by your server software.


Now you should be able to perform a `vendor/bin/mage deploy to:development`.
Be aware that Magallanes requires SSH with key authentication, you can't
use passwords for the deployment tool.

After the first deployment, you need to finish the Contao installation by
running the install tool. Point your browser to `www.example.com/install.php`
Once Contao is set up, you should download the following generated config files 
and add them to your GIT repo.
 
  - current/system/config/localconfig.php
  - current/system/config/dcaconfig.php
  - current/system/config/langconfig.php
  - current/system/config/initconfig.php

These files are necessary to run Contao, but you should rarely need to 
change them. Use `app/Resources/contao` for DCA and language customization.


Separating content and application management
---------------------------------------------

In our view, a CMS (*content management system!*) user should not maintain the
application. That's why we added some adjustments to our Contao edition:

  * The System => Settings are not available in the backend. Configuring the
    the application should not be done at runtime and not by a regular backend
    user. If you need to change a Contao setting, do that in your local copy,
    store the changes in GIT and deploy the new version!
  
  * Same applies for the template editor. Do not edit templates in your
    production installation, that's a task for an IDE and should be done
    locally. Re-deploy your application after changing templates (and 
    after you committed them to GIT for a version history)!
  
  * By default, we also removed the System => Maintenance module in the
    Contao backend. All maintenance tasks are available through the
    Symfony console on the command line, which is a much better place to
    execute them. Ideally, necessary maintenance tasks are performed during
    deployment (e.g. build internal cache) or set up as real cron jobs.

If you want/need to change any of this, just look at 
`app/Resources/contao/config.php`.



[mage]: http://magephp.com
