module.exports = {
	prefix: 'counselor-',
	purge: {
		enabled: true,
		content: ['./templates/*.php'],
	},
	darkMode: false,
	theme: {
		extend: {
			colors: {
				colbyBlue: 'rgb(0, 33, 105)',
			},
		},
	},
	variants: {
		extend: {},
	},
	plugins: [],
};
