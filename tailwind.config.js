/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                cafe: {
                    gold: "#dc8e22", // Kuning mustard
                    peach: "#fcbca4", // Pink/Peach soft
                    rust: "#a55c3c", // Merah bata
                    latte: "#a77661", // Coklat susu
                    sky: "#aebcd4", // Biru langit soft
                    coffee: "#614234", // Coklat gelap (Main text)
                },
            },
            fontFamily: {
                sans: ["Poppins", "sans-serif"],
            },
        },
    },
    plugins: [],
};
