/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './includes/Admin/templates/*.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
  mode: 'jit',
}
