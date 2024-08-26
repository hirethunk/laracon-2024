import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/joshhanley/livewire-autocomplete/resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
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
            },
            colors: {
               'gold-100-light': '#fff1c8',
               'gold-100-dark': '#e8d47a',
               'gold-500-light': '#ffe57f',
               'gold-500-dark': '#d4af37',
               'gold-900-light': '#cabc43',
               'gold-900-dark': '#b28f32',
               'link': '#679AD1',
            },
        },
    },
    variants: {
        extend: {
            backgroundImage: ['hover', 'focus']
        }
    },

    plugins: [forms],
};
