/* ====================================
   CHARTE GRAPHIQUE - DESIGN SYSTEM
   Application Laravel BTP
   ==================================== */

:root {
  /* ===== COULEURS PRINCIPALES ===== */
  --primary: #FF7A00;           /* Orange principal - Actions, CTA */
  --primary-dark: #E66A00;      /* Orange foncé - Hover */
  --primary-light: #FFB366;     /* Orange clair - Backgrounds */
  --primary-lighter: #FFF4E6;   /* Orange très clair - Zones */
  
  --secondary: #0073E6;         /* Bleu - Liens, informations */
  --secondary-dark: #0052CC;    /* Bleu foncé - Hover */
  --secondary-light: #4C9AFF;   /* Bleu clair */
  --secondary-lighter: #E6F2FF; /* Bleu très clair */
  
  /* ===== COULEURS FONCTIONNELLES ===== */
  --success: #00875A;           /* Vert - Succès, validations */
  --success-dark: #006644;      /* Vert foncé */
  --success-light: #36B37E;     /* Vert clair */
  --success-lighter: #E3FCEF;   /* Vert très clair */
  
  --warning: #FF8B00;           /* Ambre - Alertes, attention */
  --warning-dark: #FF7A00;      /* Ambre foncé */
  --warning-light: #FFAB00;     /* Ambre clair */
  --warning-lighter: #FFF7E6;   /* Ambre très clair */
  
  --danger: #DE350B;            /* Rouge - Erreurs, suppressions */
  --danger-dark: #BF2600;       /* Rouge foncé */
  --danger-light: #FF5630;      /* Rouge clair */
  --danger-lighter: #FFEBE6;    /* Rouge très clair */
  
  --info: #0065FF;              /* Bleu info - Informations neutres */
  --info-dark: #0052CC;         /* Bleu info foncé */
  --info-light: #4C9AFF;        /* Bleu info clair */
  --info-lighter: #E6F2FF;      /* Bleu info très clair */
  
  /* ===== COULEURS DE FOND ===== */
  --bg-primary: #F7F9FC;        /* Fond principal application */
  --bg-secondary: #F1F5F9;      /* Fond zones secondaires */
  --bg-tertiary: #E2E8F0;       /* Fond éléments tertiaires */
  --bg-white: #FFFFFF;          /* Blanc pur - Cards principales */
  --bg-overlay: rgba(0,0,0,0.5); /* Overlay modales */
  
  /* ===== COULEURS DE TEXTE ===== */
  --text-primary: #172B4D;      /* Texte principal - Titres */
  --text-secondary: #42526E;     /* Texte normal - Corps */
  --text-tertiary: #6B778C;     /* Texte secondaire - Sous-titres */
  --text-muted: #97A0AF;        /* Texte atténué - Placeholders */
  --text-disabled: #C1C7D0;     /* Texte désactivé */
  --text-white: #FFFFFF;        /* Texte blanc sur fonds colorés */
  
  /* ===== BORDURES ===== */
  --border-light: #DFE1E6;      /* Bordures légères */
  --border-medium: #C1C7D0;     /* Bordures normales */
  --border-strong: #97A0AF;     /* Bordures marquées */
  --border-primary: var(--primary); /* Bordures colorées */
  
  /* ===== OMBRES ===== */
  --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
  --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
  --shadow-lg: 0 10px 25px rgba(0,0,0,0.2);
  --shadow-xl: 0 20px 40px rgba(0,0,0,0.25);
  
  /* ===== RAYONS DE BORDURE ===== */
  --radius-sm: 4px;             /* Petits éléments */
  --radius-md: 8px;             /* Éléments moyens */
  --radius-lg: 12px;            /* Cards, boutons */
  --radius-xl: 16px;            /* Grandes cards */
  --radius-2xl: 24px;           /* Éléments spéciaux */
  --radius-full: 9999px;        /* Cercles parfaits */
  
  /* ===== ESPACEMENTS ===== */
  --space-1: 0.25rem;  /* 4px */
  --space-2: 0.5rem;   /* 8px */
  --space-3: 0.75rem;  /* 12px */
  --space-4: 1rem;     /* 16px */
  --space-5: 1.25rem;  /* 20px */
  --space-6: 1.5rem;   /* 24px */
  --space-8: 2rem;     /* 32px */
  --space-10: 2.5rem;  /* 40px */
  --space-12: 3rem;    /* 48px */
  --space-16: 4rem;    /* 64px */
  
  /* ===== TYPOGRAPHIE ===== */
  --font-family-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --font-family-mono: 'SF Mono', Consolas, 'Liberation Mono', Menlo, monospace;
  
  --font-size-xs: 0.75rem;      /* 12px */
  --font-size-sm: 0.875rem;     /* 14px */
  --font-size-base: 1rem;       /* 16px */
  --font-size-lg: 1.125rem;     /* 18px */
  --font-size-xl: 1.25rem;      /* 20px */
  --font-size-2xl: 1.5rem;      /* 24px */
  --font-size-3xl: 1.875rem;    /* 30px */
  --font-size-4xl: 2.25rem;     /* 36px */
  
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  
  /* ===== TRANSITIONS ===== */
  --transition-fast: 150ms ease;
  --transition-medium: 250ms ease;
  --transition-slow: 350ms ease;
  --transition-bounce: 250ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* ====================================
   CLASSES UTILITAIRES
   ==================================== */

/* ===== BACKGROUNDS ===== */
.bg-primary { background-color: var(--bg-primary); }
.bg-secondary { background-color: var(--bg-secondary); }
.bg-tertiary { background-color: var(--bg-tertiary); }
.bg-white { background-color: var(--bg-white); }

.bg-brand-primary { background-color: var(--primary); }
.bg-brand-secondary