const mix = require('laravel-mix');
const path = require('path');

/**
 * Laravel Mix Configuration
 *
 * Build configuration for CSS, JavaScript, and other assets
 */

// Set the public path for compiled assets
mix.setPublicPath('dist');

/**
 * CSS Compilation
 *
 * Compile Tailwind CSS with PostCSS
 */
mix.postCss('resources/css/app.css', 'dist/css', [
    require('postcss-import'),
    require('postcss-nested'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

/**
 * Editor Styles
 *
 * Separate styles for WordPress block editor
 */
mix.postCss('resources/css/editor.css', 'dist/css', [
    require('tailwindcss'),
    require('autoprefixer'),
]);

/**
 * JavaScript Bundling
 *
 * Bundle and transpile JavaScript with Webpack
 */
mix.js('resources/js/app.js', 'dist/js');

/**
 * Source Maps
 *
 * Generate source maps for easier debugging in development
 */
if (!mix.inProduction()) {
    mix.sourceMaps();
}

/**
 * Production Optimizations
 */
if (mix.inProduction()) {
    mix
        .version()
        .disableNotifications();
}

/**
 * Browser Sync (optional)
 *
 * Uncomment to enable automatic browser refresh during development
 * Requires configuration in .env or mix configuration
 */
// mix.browserSync({
//     proxy: 'shaganplaatjies-theme.local',
//     files: [
//         'resources/**/*',
//         'templates/**/*',
//     ],
// });

/**
 * Webpack Configuration Overrides
 */
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
        ],
    },
});
