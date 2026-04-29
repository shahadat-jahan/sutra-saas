import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Sutra Brand Colors
                'sutra-primary': '#6366f1', // Indigo
                'sutra-primary-dark': '#4f46e5',
                'sutra-primary-light': '#818cf8',
                'sutra-secondary': '#a855f7', // Purple
                'sutra-secondary-dark': '#9333ea',
                'sutra-secondary-light': '#c084fc',
                'sutra-accent': '#ec4899', // Pink
                'sutra-accent-dark': '#db2777',
                'sutra-accent-light': '#f472b6',
            },
        },
    },

    darkMode: 'class',
    plugins: [forms],
};
