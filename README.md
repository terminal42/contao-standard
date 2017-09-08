Contao edition for terminal42 gmbh
==================================

Welcome to terminal42's Contao edition, a fully-functional Contao 4 application
that you can use as the skeleton for your new applications.
The project is based on [Contao Managed Edition](https://github.com/contao/managed-edition).


What's inside?
--------------

Our Contao edition is configured with the following defaults:

  * Doctrine DBAL & ORM;
  * Doctrine ORM file caching;
  * Doctrine Migrations;
  * Gulp for assets;
  * Magallanes for deployment.

It also comes pre-configured with the following Contao extensions:

  * Ce-Access - To configure user permissions on content elements
  * Notification Center - To send notifications (e.g. email on form submit)
  * Leads - To store form submissions
  * FolderPage - To group pages in the page tree


Building your application
-------------------------

Our Contao edition contains some useful defaults we came up with
in our Contao 4 projects.

  * Deployment is pre-configured using [Magallanes][mage]. Adjust the
    environments in /.mage.yml by entering your server connection details,
    path name and the path to composer.phar file on the remote server.

  * Place your application classes in src/ and config files in app/config
    as per default Symfony best practices.
  
  * Place your style sheets in /web/layout/styles, preferrably
    using SCSS includes in the main app.scss. There's a predefined
    Gulp and deployment task which will build the optimized CSS file.
  
  * Place your javascript in /web/layout/scripts. There's a predefined
    Gulp and deployment task which will build an optimized and minified JS file.


Installing Gulp and dependencies
--------------------------------

`$ npm install`

This will make sure, everything needed to build the source JS and CSS files etc.
can be compiled into their respective targets.

If you want to know what dependencies are needed, check the `package.json`
file (or the node.js documentation).

Because calling multiple commands after each other and in the correct
order everytime you change something, we use Gulp to define tasks. A simple

`$ ./node_modules/.bin/gulp`

will execute the `default` task defined in the `gulpfile.js` and thus build
the bundled Javascript and CSS files.

If you want to change something on the source files and have Gulp rebuild
all the files everytime you save your changes, simply use

`$ ./node_modules/.bin/gulp watch`

which will keep watching all source files until you end the process.

Note: For production you should use

`$ ./node_modules/.bin/gulp --prod`

because that will enable minifying (uglyfying) JS as well as CSS files.


Setup & initial deployment
--------------------------

Before you can initially deploy your application, make sure to correctly
configure the hosting.

1. Point your VirtualHost directory to `/%vhost_root%/current/web/`

2. Create the following folders in `/%vhost_root%/shared/`:
    - config
    - files
    - images
    - logs
    - share (for sitemaps, etc.)
    - templates (only if templates are editable from backend; disabled by default)

3. Manually upload `app/config/parameters.yml.dist` to 
   `/%vhost_root%/shared/config/parameters.yml` and adjust the configuration
   (e.g. enter database credentials).

4. Adjust the `%platform_host%` parameter in the `parameters.yml` so it
   contains the publicly available URL (including the protocol) of your website.
   This will provide easy OpCode cache clearing for your setup.

5. Make sure to delete the `current/web` folder on your server if it was
   automatically created by your server software.


Now you should be able to perform a `vendor/bin/mage deploy production`.
Be aware that Magallanes requires SSH with key authentication, you can't
use passwords for the deployment tool.

After the first deployment, you need to finish the Contao installation by
running the install tool. Point your browser to `www.example.com/install.php`


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

If you want/need to change any of this, just look at `app/Resources/contao/config.php`.
