module.exports = {
  testEnvironment: 'jsdom',
  roots: ['<rootDir>/resources'],
  testMatch: ['**/__tests__/**/*.js', '**/?(*.)+(spec|test).js'],
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/resources/js/$1',
  },
  transform: {
    '^.+\\.js$': 'babel-jest',
  },
  setupFilesAfterEnv: ['<rootDir>/jest.setup.js'],
  collectCoverageFrom: [
    'resources/js/**/*.js',
    '!resources/js/**/*.spec.js',
    '!resources/js/**/*.test.js',
    '!resources/js/vendor/**',
  ],
  coverageThreshold: {
    global: {
      branches: 50,
      functions: 50,
      lines: 50,
      statements: 50,
    },
  },
};
