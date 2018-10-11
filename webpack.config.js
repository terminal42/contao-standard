var Encore = require('@symfony/webpack-encore');
var SshWebpackPlugin = require('ssh-webpack-plugin');

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
    //.addStyleEntry('global', './layout/styles/global.scss')

    // will require minified scripts without packing them
    .addLoader({
        test: /\.min\.js$/,
        use: [ 'script-loader' ]
    })

    // optimize and minify images
    .addLoader({
        test: /\.(gif|png|jpe?g|svg)$/i,
        use: [ 'image-webpack-loader' ]
    })

    // automatically upload layout files to the Contao installation
    //.addPlugin(new SshWebpackPlugin({
    //    host: 'Hostname',
    //    port: 22,
    //    username: 'Username',
    //    privateKey: require('fs').readFileSync(require('path').join(require('os').homedir(), '.ssh/id_rsa')),
    //    from: 'web/layout',
    //    to: '/path/to/public_html/web/layout',
    //    cover: false
    //}))

    // allow sass/scss files to be processed
    .enableSassLoader()

    // optimize css files
    .enablePostCssLoader()

    // allow legacy applications to use $/jQuery as a global variable
    //.autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
