const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            colors: {
                themecolor: "#BE2D87",
                black: "#3a3a3a ",
                whitelight: "rgba(255, 255, 255, 0.7)",
                lightblack: "rgba(58, 58, 58, 0.50)",
                themebgcolor: '#f6f6f6',
                deletecolor: '#fb275d',
                gray1: "#c0bcbc",
                baseColor: "#F6F3FE",
                bordercolor: '#eaeaea',
            },
            fontFamily: {
                "product_sansbold_italic": "product_sansbold_italic",
                "product_sansbold": "product_sansbold",
                "product_sansitalic": "product_sansitalic",
                "product_sans_mediumregular": "product_sans_mediumregular",
                "product_sansregular": "product_sansregular",
                "gilroy-light": "gilroy-light",
                "gilroy-regular": "gilroy-regular",
                "gilroy-medium": "gilroy-medium",
                "gilroy-semibold": "gilroy-semibold",
                "gilroy-bold": "gilroy-bold",
            },
            screens: {
                "2xl": { min: "1590px" },
                xs: { min: "480px", max: "639px" },
                "2xs": { min: "300px", max: "479px" },
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
