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
const SourceMapType = 'cheap-module-source-map';
console.warn(path.resolve(__dirname, './node_modules/'));
mix.js('resources/assets/js/app.js', 'dist/js').sourceMaps(undefined, SourceMapType);

mix.sass('resources/assets/sass/app.scss', 'dist/css', {
	includePaths: [
		'node_modules',
		'node_modules/bootstrap-sass/assets/stylesheets',
	],
}).sourceMaps(undefined, SourceMapType).options({
    postCss: [
        require('postcss-css-variables')()
    ],
});