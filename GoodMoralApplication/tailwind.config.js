import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', ...defaultTheme.fontFamily.sans],
        oldEnglish: ['"Old English Text MT Std"', 'serif'],
      },
      colors: {
        spupGreen: '#026634',
        spupGold: '#fecb04',
      },
    },
  },

  plugins: [forms],
};
