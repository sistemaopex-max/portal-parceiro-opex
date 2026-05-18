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
        'bg-green-600', 'hover:bg-green-500', 'focus:ring-green-500',
        '!bg-green-600', 'hover:!bg-green-500', 'focus:!ring-green-500',
        'border-t-2', 'border-blue-400', 'border-green-400', 'border-amber-400', 'border-red-400', 'border-gray-300',
        'shadow-card', 'shadow-card-md', 'shadow-card-hover',
        'bg-marino/10', 'bg-marino/15', 'text-marino-dark', 'border-marino-dark',
        'hover:text-marino-dark', 'focus:border-marino-dark',
        'from-marino', 'to-marino-700',
        'lg:grid-cols-[260px_minmax(0,1fr)]',
        'flex-col-reverse',
        'bottom-full',
    ],

    theme: {
        extend: {
            colors: {
                marino: {
                    50:  '#eef2f7',
                    100: '#cdd8e8',
                    200: '#acc0d9',
                    300: '#8aa7ca',
                    400: '#6990bb',
                    500: '#4878ac',
                    600: '#2d5f93',
                    700: '#1d4470',
                    DEFAULT: '#0c2340',
                    dark:    '#081a2e',
                },
                accent: {
                    DEFAULT: '#2563eb',
                    dark:    '#1d4ed8',
                    light:   '#dbeafe',
                },
            },
            boxShadow: {
                'card':       '0 1px 3px 0 rgb(0 0 0 / .06), 0 1px 2px -1px rgb(0 0 0 / .04)',
                'card-md':    '0 4px 12px -2px rgb(0 0 0 / .08), 0 2px 6px -2px rgb(0 0 0 / .05)',
                'card-hover': '0 8px 24px -4px rgb(0 0 0 / .10), 0 4px 8px -4px rgb(0 0 0 / .06)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
