module.exports = {
  root: true,
  parser: '@typescript-eslint/parser',
  plugins: ['@typescript-eslint/eslint-plugin', 'prettier'],
  extends: [
    'eslint:recommended',
    'google',
    'prettier',
    'plugin:@typescript-eslint/eslint-recommended',
    'plugin:@typescript-eslint/recommended',
    'prettier/@typescript-eslint'
  ],
  parserOptions: {
    project: 'tsconfig.json',
    sourceType: 'module'
  },
  env: {
    node: true,
    jest: true
  },
  rules: {
    '@typescript-eslint/no-unused-vars': ['error', {ignoreRestSiblings: true}],
    '@typescript-eslint/array-type': ['error', {default: 'generic'}],
    '@typescript-eslint/explicit-member-accessibility': 2,
    '@typescript-eslint/no-explicit-any': 'off',
    '@typescript-eslint/consistent-type-assertions': [
      'error',
      {assertionStyle: 'angle-bracket', objectLiteralTypeAssertions: 'never'}
    ],
    'new-cap': 'off',
    'prettier/prettier': 'error',
    'require-jsdoc': [
      'error',
      {
        require: {
          FunctionDeclaration: true,
          MethodDefinition: false,
          ClassDeclaration: false
        }
      }
    ]
  }
};
