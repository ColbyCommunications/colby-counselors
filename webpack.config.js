const path = require('path');

module.exports = {
	mode: 'development',
	entry: {
		'admin-counselors': path.resolve(__dirname, 'src/admin-counselors.index.tsx'),
		'admin-counselor-events': path.resolve(__dirname, 'src/admin-counselor-events.index.tsx'),
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
		react: 'React',
		'react-dom': 'ReactDOM',
		lodash: 'lodash',
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
