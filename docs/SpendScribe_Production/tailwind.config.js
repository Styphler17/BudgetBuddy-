/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/views/**/*.php",
    "./app/controllers/**/*.php",
    "./public/**/*.js",
    "./index.php"
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: "#10237f", // Navy
          foreground: "#ffffff",
        },
        secondary: {
          DEFAULT: "#1e293b", // Slate-800
          dark: "#055448", // Dashboard variation
          foreground: "#ffffff",
        },
        accent: {
          DEFAULT: "#a2db21", // Lime
          foreground: "#10237f",
        },
        brand: {
          DEFAULT: "#100fb0", // Indigo
          foreground: "#ffffff",
        },
        success: {
          DEFAULT: "#10b981",
          light: "#b3f8b1", // Dashboard variation
          dark: "#055448", // Dashboard variation
        },
        warning: "#f59e0b",
        destructive: "#ef4444",
      },
      fontFamily: {
        inter: ['Inter', 'sans-serif'],
        outfit: ['Outfit', 'sans-serif'],
      },
      fontWeight: {
        display: ' display', // Match current settings
        h1: '700',
        h2: '600',
        body: '400',
      },
      spacing: {
        '8px': '8px',
        '16px': '16px',
        '24px': '24px',
        '32px': '32px',
      },
      boxShadow: {
        toast: "0px 32px 64px -16px rgba(0,0,0,0.30), 0px 16px 32px -8px rgba(0,0,0,0.30), 0px 8px 16px -4px rgba(0,0,0,0.24), 0px 4px 8px -2px rgba(0,0,0,0.24), 0px -8px 16px -1px rgba(0,0,0,0.16), 0px 2px 4px -1px rgba(0,0,0,0.24), 0px 0px 0px 1px rgba(0,0,0,1.00), inset 0px 0px 0px 1px rgba(255,255,255,0.08), inset 0px 1px 0px 0px rgba(255,255,255,0.20)"
      }
    }
  },
  plugins: [],
}
