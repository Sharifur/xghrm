const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.options({legacyNodePolyfills: false})
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('autoprefixer'),
    ])
    .sass("resources/scss/main-style.scss", "public/css")
    .sass("resources/scss/loginscreen.scss", "public/css")
    .options({
        postCss: [
            require("autoprefixer"),
        ],
    })
    .js("resources/js/main-script.js","public/js")
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}

