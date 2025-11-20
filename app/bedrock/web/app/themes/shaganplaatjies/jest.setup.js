/**
 * Jest Setup File
 *
 * Configure Jest before running tests
 */

// Mock WordPress global objects if needed
if (typeof window !== 'undefined') {
  // Mock wp global object
  window.wp = window.wp || {};
  window.wp.i18n = {
    __: (text) => text,
    _x: (text) => text,
    _n: (singular, plural, count) => (count === 1 ? singular : plural),
  };
}

// Mock fetch if needed
global.fetch = jest.fn(() =>
  Promise.resolve({
    json: () => Promise.resolve({}),
  })
);

// Suppress console errors in tests (optional)
const originalError = console.error;
beforeAll(() => {
  console.error = (...args) => {
    if (
      typeof args[0] === 'string' &&
      args[0].includes('Warning: ReactDOM.render')
    ) {
      return;
    }
    originalError.call(console, ...args);
  };
});

afterAll(() => {
  console.error = originalError;
});
