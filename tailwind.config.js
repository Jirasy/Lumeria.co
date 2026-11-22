import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/*
 | Palet Lumeria untuk halaman Tailwind (login, register, dashboard, profil).
 |
 | Halaman publik (Bootstrap) mengambil warna dari token di public/css/lumeria.css.
 | Di sini palet bawaan Tailwind (gray, indigo) diganti dengan warna logo dan deck
 | Lumeria, sehingga semua kelas Breeze yang sudah ada (bg-gray-800, focus:ring-indigo-500, ...)
 | otomatis ikut. Warna kunci HARUS sama dengan token di lumeria.css
 | (dijaga oleh ThemeTest::test_tailwind_palette_matches_the_theme_tokens):
 |
 |   gray-100  = --lumeria-surface   gray-200 = --lumeria-border   gray-500 = --lumeria-muted
 |   gray-800  = --lumeria-ink       gray-900 = --lumeria-darker   indigo-500 = --lumeria-dark
 |
 | Tema Lumeria hanya terang, jadi darkMode memakai 'class' (tidak ikut mode gelap sistem).
 | Setelah mengubah file ini jalankan ulang `npm run dev` atau `npm run build`.
 */
const lumeriaGray = {
    50:  '#FBF9F5',
    100: '#F7F4EE',
    200: '#E4DFD3',
    300: '#CFC9BB',
    400: '#8E96A2',
    500: '#5B6573',
    600: '#475266',
    700: '#34425A',
    800: '#1B2A40',
    900: '#132238',
};

const lumeriaBlue = {
    50:  '#EEF2F7',
    100: '#DCE4EE',
    200: '#C8D2DE',
    300: '#9CB0C8',
    400: '#6B87AE',
    500: '#31507A',
    600: '#2A4468',
    700: '#24405F',
    800: '#1E3350',
    900: '#17293F',
};

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

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
                gray: lumeriaGray,
                indigo: lumeriaBlue,
            },
        },
    },

    plugins: [forms],
};
