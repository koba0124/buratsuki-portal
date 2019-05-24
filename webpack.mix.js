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

mix.js('resources/js/materialize.js', 'public/assets/js')
	.js('resources/js/template.js', 'public/assets/js')
	.js('resources/js/cards_index.js', 'public/assets/js')
	.js('resources/js/home_edit_profile.js', 'public/assets/js')
	.js('resources/js/games_create.js', 'public/assets/js')
	.js('resources/js/games_edit.js', 'public/assets/js')
	.js('resources/js/games_view.js', 'public/assets/js')
	.js('resources/js/statistics_cardsuses.js', 'public/assets/js')
	.js('resources/js/statistics_score.js', 'public/assets/js')
	.js('resources/js/statistics_user.js', 'public/assets/js')
	.sass('resources/sass/app.scss', 'public/assets/css')
	.sass('resources/sass/app_async.scss', 'public/assets/css');