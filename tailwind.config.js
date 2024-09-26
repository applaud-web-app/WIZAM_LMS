/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.tsx",
    "./resources/**/*.jsx",
    "./node_modules/tw-elements/js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        primary: '#10B981',
      },
    },
  },
  plugins: [
    require("tw-elements/plugin.cjs"),
  ]
}