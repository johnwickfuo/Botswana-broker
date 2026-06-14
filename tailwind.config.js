/*
|--------------------------------------------------------------------------
| Tailwind CSS — Republic of Botswana Investment Platform
|--------------------------------------------------------------------------
|
| Phase 1: palette tokens only (no full rebrand yet). Colours are taken
| from the national flag so future components use the correct palette
| from the start:
|   - botswana.blue      #00A3DD  (primary sky blue)
|   - botswana.blue-alt  #75AADB  (alternate sky blue)
|   - botswana.black     #111111  (near-black structure / text)
|   - white background
| The thin white-bordered black divider (a flag nod) is exposed via the
| `botswana.divider` border colour token.
|
*/

module.exports = {
    purge: {
        content: [
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './themes/**/*.blade.php',
        ],
    },
    darkMode: false,
    theme: {
        extend: {
            colors: {
                botswana: {
                    DEFAULT: '#00A3DD',
                    blue: '#00A3DD',
                    'blue-alt': '#75AADB',
                    black: '#111111',
                    white: '#FFFFFF',
                    divider: '#111111',
                },
                // Convenience semantic aliases
                primary: '#00A3DD',
                ink: '#111111',
            },
            fontFamily: {
                // Clean institutional sans-serif (loaded in a later branding phase)
                sans: ['Inter', 'Public Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
};
