/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')

module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/**/*.php', // Pour les classes dans les modèles
  ],
  theme: {
    extend: {
      colors: {
        // Couleurs modernes principales
        primary: {
          50: '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#4f46e5', // Couleur principale
          600: '#4338ca',
          700: '#3730a3',
          800: '#312e81',
          900: '#1e1b4b',
        },
        
        // Palette complète moderne
        slate: colors.slate,      // Nouveaux neutres
        indigo: colors.indigo,    // Nouvelle couleur principale
        purple: colors.purple,    // Pour les gradients
        emerald: colors.emerald,  // Success moderne
        cyan: colors.cyan,        // Info moderne
        amber: colors.amber,      // Warning moderne
        
        // Couleurs métier BTP
        btp: {
          orange: '#ff7a00',      // Votre orange existant (pour transition)
          blue: '#0073e6',        // Votre bleu existant (pour transition)
          concrete: '#95a5a6',    // Gris béton
          safety: '#f39c12',      // Orange sécurité
          earth: '#8b4513',       // Terre/bois
        },
        
        // États modernes
        success: colors.emerald,
        warning: colors.amber,
        danger: colors.red,
        info: colors.cyan,
      },
      
      fontFamily: {
        sans: [
          'Inter', 
          '-apple-system', 
          'BlinkMacSystemFont', 
          '"Segoe UI"', 
          'Roboto', 
          '"Helvetica Neue"', 
          'Arial', 
          'sans-serif'
        ],
        mono: [
          '"SF Mono"',
          'Consolas', 
          '"Liberation Mono"', 
          'Menlo', 
          'monospace'
        ],
      },
      
      fontSize: {
        '2xs': '0.6875rem',     // 11px
        'xs': '0.75rem',        // 12px
        'sm': '0.875rem',       // 14px
        'base': '1rem',         // 16px
        'lg': '1.125rem',       // 18px
        'xl': '1.25rem',        // 20px
        '2xl': '1.5rem',        // 24px
        '3xl': '1.875rem',      // 30px
        '4xl': '2.25rem',       // 36px
        '5xl': '3rem',          // 48px
        '6xl': '3.75rem',       // 60px
        '7xl': '4.5rem',        // 72px
      },
      
      spacing: {
        '18': '4.5rem',        // 72px
        '88': '22rem',         // 352px
        '100': '25rem',        // 400px
        '112': '28rem',        // 448px
        '128': '32rem',        // 512px
      },
      
      maxWidth: {
        '8xl': '88rem',        // 1408px
        '9xl': '96rem',        // 1536px
      },
      
      borderRadius: {
        'none': '0',
        'sm': '0.25rem',       // 4px
        'DEFAULT': '0.5rem',   // 8px
        'md': '0.75rem',       // 12px
        'lg': '1rem',          // 16px
        'xl': '1.5rem',        // 24px
        '2xl': '2rem',         // 32px
        '3xl': '3rem',         // 48px
        'full': '9999px',
      },
      
      boxShadow: {
        'soft': '0 4px 24px rgba(15, 23, 42, 0.08)',
        'medium': '0 8px 32px rgba(15, 23, 42, 0.12)',
        'strong': '0 16px 48px rgba(15, 23, 42, 0.16)',
        'glow': '0 0 20px rgba(79, 70, 229, 0.3)',
        'glow-lg': '0 0 40px rgba(79, 70, 229, 0.4)',
        'inner-soft': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
      },
      
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'fade-in-up': 'fadeInUp 0.6s ease-out',
        'slide-down': 'slideDown 0.3s ease-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'slide-in-right': 'slideInRight 0.3s ease-out',
        'pulse-slow': 'pulse 3s infinite',
        'pulse-glow': 'pulseGlow 2s infinite',
        'shimmer': 'shimmer 2s infinite',
        'float': 'float 3s ease-in-out infinite',
        'bounce-gentle': 'bounceGentle 2s infinite',
        'spin-slow': 'spin 3s linear infinite',
      },
      
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { 
            opacity: '0',
            transform: 'translateY(20px)' 
          },
          '100%': { 
            opacity: '1',
            transform: 'translateY(0)' 
          },
        },
        slideDown: {
          '0%': { 
            opacity: '0',
            transform: 'translateY(-10px)' 
          },
          '100%': { 
            opacity: '1',
            transform: 'translateY(0)' 
          },
        },
        slideUp: {
          '0%': { 
            opacity: '0',
            transform: 'translateY(10px)' 
          },
          '100%': { 
            opacity: '1',
            transform: 'translateY(0)' 
          },
        },
        slideInRight: {
          '0%': { 
            transform: 'translateX(100%)',
            opacity: '0' 
          },
          '100%': { 
            transform: 'translateX(0)',
            opacity: '1' 
          },
        },
        pulseGlow: {
          '0%, 100%': { 
            boxShadow: '0 0 5px rgba(79, 70, 229, 0.5)' 
          },
          '50%': { 
            boxShadow: '0 0 20px rgba(79, 70, 229, 0.8), 0 0 30px rgba(79, 70, 229, 0.6)' 
          },
        },
        shimmer: {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(100%)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        bounceGentle: {
          '0%, 20%, 53%, 80%, 100%': { 
            transform: 'translate3d(0,0,0)' 
          },
          '40%, 43%': { 
            transform: 'translate3d(0, -8px, 0)' 
          },
          '70%': { 
            transform: 'translate3d(0, -4px, 0)' 
          },
          '90%': { 
            transform: 'translate3d(0, -2px, 0)' 
          },
        },
      },
      
      backdropBlur: {
        'xs': '2px',
        'sm': '4px',
        'md': '12px',
        'lg': '16px',
        'xl': '24px',
        '2xl': '40px',
        '3xl': '64px',
      },
      
      transitionTimingFunction: {
        'bounce-in': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
        'smooth': 'cubic-bezier(0.4, 0, 0.2, 1)',
        'swift': 'cubic-bezier(0.4, 0.0, 0.2, 1)',
      },
      
      transitionDuration: {
        '250': '250ms',
        '350': '350ms',
        '400': '400ms',
        '600': '600ms',
        '800': '800ms',
        '1200': '1200ms',
      },
      
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
      
      // Configuration pour les breakpoints
      screens: {
        'xs': '475px',
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
        '3xl': '1792px',
      },
      
      // Typographie avancée
      lineHeight: {
        'tight': '1.15',
        'snug': '1.375',
        'normal': '1.5',
        'relaxed': '1.625',
        'loose': '2',
        '12': '3rem',
        '16': '4rem',
      },
      
      letterSpacing: {
        'tighter': '-0.05em',
        'tight': '-0.025em',
        'normal': '0',
        'wide': '0.025em',
        'wider': '0.05em',
        'widest': '0.1em',
      },
    },
  },
  
  plugins: [
    require('@tailwindcss/forms')({
      strategy: 'class', // Utilise la stratégie de classe pour éviter les conflits
    }),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    
    // Plugin personnalisé pour le design system BTP
    function({ addComponents, theme }) {
      addComponents({
        // Styles pour les états de chantier
        '.status-indicator': {
          display: 'inline-flex',
          alignItems: 'center',
          gap: theme('spacing.2'),
          padding: `${theme('spacing.1')} ${theme('spacing.3')}`,
          borderRadius: theme('borderRadius.full'),
          fontSize: theme('fontSize.sm'),
          fontWeight: theme('fontWeight.medium'),
        },
        
        // Barres de progression personnalisées
        '.progress-enhanced': {
          position: 'relative',
          overflow: 'hidden',
          '&::after': {
            content: '""',
            position: 'absolute',
            top: '0',
            left: '-100%',
            width: '100%',
            height: '100%',
            background: 'linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent)',
            animation: 'shimmer 2s infinite',
          },
        },
        
        // Cards avec effets spéciaux
        '.card-hover-lift': {
          transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
          '&:hover': {
            transform: 'translateY(-4px)',
            boxShadow: theme('boxShadow.strong'),
          },
        },
        
        // Boutons avec loading state
        '.btn-loading': {
          position: 'relative',
          pointerEvents: 'none',
          '&::after': {
            content: '""',
            position: 'absolute',
            top: '50%',
            left: '50%',
            width: '16px',
            height: '16px',
            margin: '-8px 0 0 -8px',
            border: '2px solid transparent',
            borderTop: '2px solid currentColor',
            borderRadius: '50%',
            animation: 'spin 1s linear infinite',
          },
        },
      })
    },
  ],
  
  // Classes importantes à préserver lors du build
  safelist: [
    // États dynamiques
    'bg-slate-100',
    'bg-indigo-100',
    'bg-emerald-100',
    'bg-red-100',
    'bg-amber-100',
    'bg-cyan-100',
    
    // Textes colorés
    'text-slate-800',
    'text-indigo-800',
    'text-emerald-800',
    'text-red-800',
    'text-amber-800',
    'text-cyan-800',
    
    // Bordures colorées
    'border-slate-200',
    'border-indigo-200',
    'border-emerald-200',
    'border-red-200',
    'border-amber-200',
    'border-cyan-200',
    
    // Indicateurs de statut
    'bg-slate-400',
    'bg-blue-500',
    'bg-emerald-500',
    'bg-red-500',
    'bg-amber-500',
    
    // Classes générées par PHP
    {
      pattern: /(bg|text|border)-(slate|indigo|emerald|red|amber|cyan)-(50|100|200|300|400|500|600|700|800|900)/,
    },
    
    // Progress bars
    {
      pattern: /w-(1|2|3|4|5|6|7|8|9|10|11|12)\/12/,
    },
    {
      pattern: /w-\[(0-9)+%\]/,
    },
    
    // Animations et états
    'animate-pulse',
    'animate-spin',
    'animate-bounce',
    'animate-fade-in',
    'animate-slide-down',
    'hover:scale-105',
    'hover:-translate-y-1',
    'transition-all',
    'duration-200',
    'duration-300',
    'ease-in-out',
  ],
  
  // Configuration future-proofing
  future: {
    hoverOnlyWhenSupported: true,
  },
  
  // Support expérimental
  experimental: {
    optimizeUniversalDefaults: true,
  },
}