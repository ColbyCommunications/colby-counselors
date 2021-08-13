let mix = require('laravel-mix');

require('mix-tailwindcss');

mix.ts('src/admin-counselors.index.tsx', 'dist/admin-counselors.js');

mix.postCss('css/app.css', 'dist/app.css').tailwind();

mix.webpackConfig({
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
		lodash: 'lodash',
		'colby-counselors-backend': 'colbyCounselorsBackend',
	},
	resolve: {
		extensions: ['.tsx', '.ts', '.js'],
	},
});
