var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')

    // will create public/build/app.js and public/build/app.css
    .addEntry('app', './web/assets/js/app.js')

    // allow sass/scss files to be processed
    .enableSassLoader()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

    .enableReactPreset()

    // create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning()

    .configureBabel(function(babelConfig) {
        // no plugins are added by default, but you can add some
        babelConfig.plugins.push('transform-object-rest-spread');
        babelConfig.plugins.push('babel-plugin-transform-decorators-legacy');
        babelConfig.plugins.push('babel-plugin-transform-decorators');
    })
;

// export the final configuration
module.exports = Encore.getWebpackConfig();