module.exports = {
    preset: '@vue/cli-plugin-unit-jest/presets/no-babel',
    testMatch: [
        '**/tests/unit/**/*.spec.[jt]s?(x)',
        '**/__tests__/*.[jt]s?(x)',
        '**.spec.js',
    ],
};
