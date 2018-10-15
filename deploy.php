<?php

namespace Deployer;

use Deployer\Exception\RuntimeException;

$recipes = ['common', 'rsync'];

// Require the recipes
foreach ($recipes as $recipe) {
    require_once sprintf('recipe/%s.php', $recipe);
}

// Load the hosts
inventory('deploy-hosts.yml');

/**
 * ===============================================================
 * Configuration
 *
 * Define the deployment configuration. Each of the variables
 * can be overridden individually per each host.
 * ===============================================================
 */
// Enable SSH multiplexing
set('ssh_multiplexing', true);

// Number of releases to keep
set('keep_releases', 3);

// Disable anonymous stats
set('allow_anonymous_stats', false);

// Rsync
set('rsync_src', __DIR__);
set('rsync', function () {
    return [
        'exclude' => array_unique(array_merge(
            [
                '._*',
                '.babelrc',
                '.DS_Store',
                '.eslintrc.json',
                '.git',
                '.gitignore',
                '.idea',
                '.php_cs',
                '.php_cs.cache',
                'deploy.php',
                'deploy-hosts.yml',
                'composer.json~',
                'package.json',
                'package.lock',
                '.env.local',

                '/app/Resources/contao/config/runonce*',
                '/assets',
                '/files',
                '/layout',
                '/node_modules',
                '/system',
                '/tests',
                '/var',
                '/vendor',
                '/web/assets',
                '/web/bundles',
                '/web/files',
                '/web/share',
                '/web/system',
                '/web/app.php',
                '/web/app_dev.php',
                '/phpunit.*',
                '/README.md',
            ],
            get('exclude', [])
        )),
        'exclude-file' => false,
        'include' => [],
        'include-file' => false,
        'filter' => [],
        'filter-file' => false,
        'filter-perdir' => false,
        'flags' => 'rz',
        'options' => ['delete'],
        'timeout' => 300,
    ];
});

// Environment
set('symfony_env', 'prod');

// Initial directories
set('initial_dirs', ['assets', 'system', 'var', 'web']);

// Shared directories
set('shared_dirs', [
    'assets/images',
    'files',
    'var/logs',
    'web/share',
]);

// Shared files
set('shared_files', ['.env.local']);

// Writable dirs
set('writable_dirs', ['var']);

// Console bin
set('bin/console', function () {
    return '{{release_path}}/vendor/bin/contao-console';
});

// Console options
set('console_options', '--no-interaction --env={{symfony_env}}');

/**
 * ===============================================================
 * Tasks
 *
 * Define the deployment tasks.
 * ===============================================================
 */

// Validate local setup
task('deploy:validate_local', function () {
    run('./vendor/bin/contao-console contao:version');
})->desc('Validate local setup')->local();

// Compile assets
task('deploy:compile_assets', function () {
    run('npm run prod');
})->desc('Compile assets')->local();

// Update the Composer
task('deploy:composer_self_update', function () {
    run('cd {{release_path}} && {{bin/composer}} self-update');
})->desc('Composer self-update');

// Create initial directories task
task('deploy:create_initial_dirs', function () {
    foreach (get('initial_dirs') as $dir) {
        // Set dir variable
        set('_dir', '{{release_path}}/' . $dir);

        // Create dir if it does not exist
        run('if [ ! -d "{{_dir}}" ]; then mkdir -p {{_dir}}; fi');

        // Set rights
        run("chmod -R g+w {{_dir}}");
    }
})->desc('Create initial dirs');

// Cache accelerator cache
task('cache:accelerator_clear', function () {
    try {
        run('cd {{release_path}} && {{bin/composer}} show smart-core/accelerator-cache-bundle');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[40C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} cache:accelerator:clear {{console_options}}');
})->desc('Clear accelerator cache');

// Migrate database
task('database:migrate', function () {
    try {
        run('cd {{release_path}} && {{bin/composer}} show doctrine/doctrine-migrations-bundle');
    } catch (RuntimeException $e) {
        writeln("\r\033[1A\033[33C … skipped");

        /** @noinspection PhpUndefinedMethodInspection */
        output()->setWasWritten(false);

        return;
    }

    run('{{bin/php}} {{bin/console}} doctrine:migrations:migrate {{console_options}} --allow-no-migration');
})->desc('Migrate database');

// Enable maintenance mode
task('maintenance:enable', function () {
    run('{{bin/php}} {{bin/console}} lexik:maintenance:lock {{console_options}}');
})->desc('Enable maintenance mode');

// Disable maintenance mode
task('maintenance:disable', function () {
    run('{{bin/php}} {{bin/console}} lexik:maintenance:unlock {{console_options}}');
})->desc('Disable maintenance mode');

// Main task
task('deploy', [
    'deploy:validate_local',
    'deploy:compile_assets',
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:create_initial_dirs',
    'deploy:shared',
    'deploy:composer_self_update',
    'deploy:vendors',
    'maintenance:enable',
    'deploy:symlink',
    'cache:accelerator_clear',
    'database:migrate',
    'maintenance:disable',
    'deploy:unlock',
    'cleanup',
    'success',
])->desc('Deploy your project');
