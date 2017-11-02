var Encore = require('@symfony/webpack-encore');

Encore
    // directory where all compiled assets will be stored
    .setOutputPath('web/layout/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/layout')

    // removes the /layout prefix from assets paths
    .setManifestKeyPrefix('')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/layout/app.js
    .addEntry('app', './layout/scripts/app.js')

    // will output as web/layout/global.css
    //.addStyleEntry('global', './layout/layout/global.scss')

    // allow sass/scss files to be processed
    .enableSassLoader()

    // allow legacy applications to use $/jQuery as a global variable
    //.autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
