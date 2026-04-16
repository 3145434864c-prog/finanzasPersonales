/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/**/*.php',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
