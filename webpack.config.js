const path = require('path');

module.exports = {
	mode: 'development',
	entry: {
		'admin-counselors': ['@babel/polyfill', path.resolve(__dirname, 'src/admin-counselors.index.tsx')],
		'admin-counselor-events': ['@babel/polyfill', path.resolve(__dirname, 'src/admin-counselor-events.index.tsx')],
	},
	module: {
		rules: [
			{
				test: /\.tsx?$/,
				use: 'ts-loader',
				exclude: /node_modules/,
			},
		],
	},
	externals: {
		'colby-counselors-backend': 'colbyCounselorsBackend',
	},
	resolve: {
		extensions: ['.tsx', '.ts', '.js'],
	},
	output: {
		filename: '[name].js',
		path: path.resolve(__dirname, 'dist'),
	},
};
