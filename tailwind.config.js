import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-green-600',
        'hover:bg-green-500',
        'focus:ring-green-500',
        '!bg-green-600',
        'hover:!bg-green-500',
        'focus:!ring-green-500',
    ],

    theme: {
        extend: {
            colors: {
                marino: {
                    DEFAULT: '#0c2340',
                    dark: '#081a2e',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
