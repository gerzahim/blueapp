const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |

/*
mix.js('resources/js/app.js', 'public/js')
   .styles('public/adminmart/css/style.css', 'public/css/app.css')
   .version();
*/


mix.webpackConfig({
        output:{
            chunkFilename: 'js/components/[name].[contenthash].js',
        }
    })
    .js('resources/js/app.js', 'public/js')
    .styles('public/adminmart/css/style.css', 'public/css/app.css')
    .version();

