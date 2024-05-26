/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.html",
    "./public/**/*.js",
    "./public/**/*.php",  // Include PHP files
    "./src/*.php",
    "./src/**/*.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
