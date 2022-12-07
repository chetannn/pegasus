module.exports = {
  env: {
    node: true
  },
  extends: [
    'eslint:recommended',
    'plugin:vue/vue3-recommended',
    'plugin:import/recommended'
  ],
  globals: {
    'axios': 'readonly',
    'route': 'readonly',
  },
  overrides: [
  ],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module'
  },
  rules: {
    'array-bracket-spacing': ['warn', 'never'],
    'comma-dangle': ['warn', 'only-multiline'],
    'import/order': 'warn',
    'indent': ['warn', 2],
    'key-spacing': ['warn', { beforeColon: false, afterColon: true }],
    'linebreak-style': ['error', 'unix'],
    'no-console': 'warn',
    'object-curly-spacing': ['warn', 'always'],
    'quotes': ['warn', 'single', { avoidEscape: true, allowTemplateLiterals: true }],
    'semi': ['warn', 'never'],
    'vue/component-name-in-template-casing': ['error', 'PascalCase', { registeredComponentsOnly: false }],
    'vue/multi-word-component-names': 'off',
  }
}
