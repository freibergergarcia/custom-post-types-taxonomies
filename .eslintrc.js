module.exports = {
	env: {
		browser: true,
		node: true,
		es6: true,
	},
	extends: [ 'eslint:recommended' ],
	parserOptions: {
		ecmaVersion: 2020,
		sourceType: 'module',
	},
	overrides: [
		{
			files: [ '**/*.test.js', '**/*.spec.js', 'tests/**/*.js' ],
			env: {
				jest: true,
				node: true,
			},
			rules: {
				// Allow console.log in tests for debugging
				'no-console': 'off',
			},
		},
		{
			files: [ 'tests/e2e/**/*.js' ],
			env: {
				node: true,
			},
			globals: {
				page: 'readonly',
				browser: 'readonly',
				context: 'readonly',
				expect: 'readonly',
				test: 'readonly',
			},
			rules: {
				// Playwright specific rules
				'no-console': 'off',
				'no-unused-vars': [ 'error', { argsIgnorePattern: '^_' } ],
			},
		},
	],
	rules: {
		// Basic code quality rules
		'no-unused-vars': 'error',
		'no-undef': 'error',
		'semi': [ 'error', 'always' ],
		'quotes': [ 'error', 'single' ],
		'indent': [ 'error', 'tab' ],
		'no-trailing-spaces': 'error',
		'eol-last': 'error',
	},
};
