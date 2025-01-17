/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./storage/app/xml/**/*.xsl",
    ],
    safelist: [
        "bg-green-600",
        "bg-yellow-600",
        "bg-purple-600",
        "bg-indigo-600",
        "bg-pink-600",
        "bg-sky-600",
        "bg-teal-600",
        "bg-cyan-600",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    600: "#e21655",
                    500: "#e94466",
                    400: "#ef6177",
                    300: "#f57a89",
                    200: "#f9929c",
                    100: "#fda8af",
                },
                darkSurface: {
                    600: "#121212",
                    500: "#282828",
                    400: "#3f3f3f",
                    300: "#575757",
                    200: "#717171",
                    100: "#8b8b8b",
                },
                darkMixed: {
                    600: "#251718",
                    500: "#3a2c2d",
                    400: "#504343",
                    300: "#675b5b",
                    200: "#7e7474",
                    100: "#978e8f",
                },
            },
        },
        fontFamily: {
            inter: ["Inter", "sans-serif"],
        },
    },
    plugins: [],
};
