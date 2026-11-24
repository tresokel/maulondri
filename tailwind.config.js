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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                raptors: {
                    purple: '#5E2B97',
                    lightpurple: '#753BBD',
                    darkpurple: '#3B1564',
                    red: '#CE1141',
                    darkred: '#9E0C30',
                    silver: '#A1A1A4',
                    black: '#151319',
                },
            },
        },
    },

    plugins: [forms],
};
