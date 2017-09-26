let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.combine([
    '.vendor/jeroennoten/laravel-adminlte/resources/assets/bootstrap/js/*',
    '.vendor/jeroennoten/laravel-adminlte/resources/assets/dist/js/*',
    '.vendor/jeroennoten/laravel-adminlte/resources/assets/plugins/js/*'
], 'public/js/admin.js');

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
