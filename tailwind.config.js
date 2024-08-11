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
                'serif': ['Cinzel', 'Georgia', 'Times New Roman', 'serif'],
                'sans': ['Rubik', 'Arial', 'Helvetica', 'sans-serif'],
            },
            backgroundImage: {
                'gold-100': 'var(--gold-100)',
                'gold-500': 'var(--gold-500)',
                'gold-900': 'var(--gold-900)',
            }
        },
    },
    variants: {
        extend: {
            backgroundImage: ['hover', 'focus']
        }
    },

    plugins: [forms],
};
