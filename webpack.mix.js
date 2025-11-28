const mix = require('laravel-mix');

mix.sass('resources/sass/styles.scss', 'public/css', {
    sassOptions: {
        quietDeps: true,
        silenceDeprecations: ['import'],
        verbose: false
    }
})
.js('resources/js/main.js', 'public/js')
.sourceMaps();