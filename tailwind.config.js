/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Livewire/**/*.php',
  ],
  theme: {
    extend: {
      // ── Paleta de marca SistemasEscolares ──────────────────────
      colors: {
        brand: {
          jet:       '#333333',  // texto principal, fondos oscuros (sidebar)
          primary:   '#40848D',  // dark cyan — acciones primarias, focus ring
          secondary: '#739FA5',  // moonstone — botones secundarios, badges
          surface:   '#C1D7DA',  // light blue — fondos sutiles, hover, chips
          white:     '#FFFFFF',
        },
        // Escala completa de primary para variantes de estado
        primary: {
          50:  '#f0f8f9',
          100: '#d9eff2',
          200: '#b5dfe5',
          300: '#82c6cf',
          400: '#4ea5b1',
          500: '#40848D', // base
          600: '#346f77',
          700: '#2c5c63',
          800: '#264d53',
          900: '#1e3d43',
          950: '#112428',
        },
        // Escala neutral (grises cálidos con tinte cyan)
        neutral: {
          50:  '#f7f9f9',
          100: '#edf1f2',
          200: '#d7e0e2',
          300: '#bac9cc',
          400: '#96afb3',
          500: '#77969b',
          600: '#5f7a7e',
          700: '#4e6568',
          800: '#3d4f52',  // ≈ brand.jet con tinte
          900: '#2e3a3c',
          950: '#1a2122',
        },
        // Semánticos
        success: {
          50:  '#f0faf0',
          400: '#4caf50',
          500: '#388e3c',
          600: '#2e7d32',
          700: '#1b5e20',
        },
        warning: {
          50:  '#fffbf0',
          400: '#ffb74d',
          500: '#f57c00',
          600: '#e65100',
        },
        danger: {
          50:  '#fdf2f2',
          100: '#fce8e8',
          300: '#f9a8a8',
          400: '#f47777',
          500: '#e53935',
          600: '#c62828',
          700: '#b71c1c',
        },
        info: {
          50:  '#f0f7ff',
          400: '#64b5f6',
          500: '#1976d2',
          600: '#1565c0',
        },
      },
      // ── Tipografía System UI (sin Google Fonts) ────────────────
      fontFamily: {
        sans: [
          '-apple-system',
          '"Segoe UI"',
          'Roboto',
          '"Helvetica Neue"',
          'Arial',
          'sans-serif',
        ],
        mono: [
          '"SFMono-Regular"',
          'Consolas',
          '"Liberation Mono"',
          'Menlo',
          'monospace',
        ],
      },
      // ── Tamaños / espaciados extra ──────────────────────────────
      fontSize: {
        '2xs': ['0.625rem', { lineHeight: '0.875rem' }],
      },
      // ── Sombras ─────────────────────────────────────────────────
      boxShadow: {
        card:   '0 1px 3px 0 rgb(0 0 0 / 0.07), 0 1px 2px -1px rgb(0 0 0 / 0.07)',
        modal:  '0 20px 60px -10px rgb(0 0 0 / 0.25)',
        focus:  '0 0 0 3px rgb(64 132 141 / 0.35)',
      },
      // ── Breakpoint extra para mobile-first autogestión ──────────
      screens: {
        xs: '380px',
      },
      // ── Animaciones ─────────────────────────────────────────────
      keyframes: {
        'fade-in': {
          from: { opacity: '0', transform: 'translateY(-4px)' },
          to:   { opacity: '1', transform: 'translateY(0)' },
        },
        'slide-in-right': {
          from: { opacity: '0', transform: 'translateX(12px)' },
          to:   { opacity: '1', transform: 'translateX(0)' },
        },
        'progress-indeterminate': {
          '0%':   { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(400%)' },
        },
      },
      animation: {
        'fade-in':                'fade-in 0.15s ease-out',
        'slide-in-right':         'slide-in-right 0.2s ease-out',
        'progress-indeterminate': 'progress-indeterminate 1.2s ease-in-out infinite',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
