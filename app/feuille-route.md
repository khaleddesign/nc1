📋 FEUILLE DE ROUTE POUR LES DESIGNERS
🎯 OBJECTIF
Transformer l'interface N-C BTP pour qu'elle ressemble exactement à cette référence moderne avec :

Palette indigo/purple moderne
Cards avec glassmorphism subtil
Animations fluides et micro-interactions
Layout sidebar + content zone


🎨 1. DESIGN SYSTEM À APPLIQUER
Palette de couleurs (à utiliser partout)
css--primary: #4f46e5 (indigo moderne)
--primary-light: #6366f1
--primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)

--neutral-50: #f8fafc (fond app)
--neutral-100: #f1f5f9 (zones secondaires)
--neutral-200: #e2e8f0 (bordures)
--neutral-500: #64748b (texte secondaire)
--neutral-800: #1e293b (texte principal)

--shadow-soft: 0 4px 24px rgba(15, 23, 42, 0.08)
--shadow-medium: 0 8px 32px rgba(15, 23, 42, 0.12)
Typographie

Police : Inter (comme la référence)
Hiérarchie : font-weight de 400 à 700
Tailles : 0.75rem à 2.5rem avec progression harmonieuse

Espacements & Bordures

Border-radius : 8px (petits), 12px (cards), 16px+ (grands éléments)
Padding : Système par multiples de 0.5rem (8px)
Gaps : 1rem à 2rem entre éléments


🏗️ 2. COMPOSANTS À DESIGNER
A. Layout Principal

Sidebar fixe : 280px de large, fond blanc, ombre douce
Header : Collant, fond blanc, barre de recherche centrée
Zone principale : Grid responsive avec padding 2rem

B. Navigation Sidebar
css✅ Logo avec icône en dégradé + texte
✅ Sections avec titres en uppercase gris
✅ Items avec icônes + badges numériques
✅ État actif : bordure droite colorée + fond dégradé
✅ Hover : fond gris léger + couleur primaire
C. Cards Modernes
css✅ Fond blanc pur
✅ Ombre douce (shadow-soft)
✅ Coins arrondis (12px)
✅ Hover : élévation + légère translation Y
✅ Header avec titre + icône ronde colorée
✅ Body avec padding harmonieux
D. Boutons d'Action
css✅ Style "dashed border" par défaut
✅ Hover : transformation en bouton plein coloré
✅ Icône + texte centrés verticalement
✅ Animation de translation Y au hover
E. Stats Cards
css✅ Numéros géants (2.5rem, font-weight: 700)
✅ Indicateurs colorés (vert/rouge) avec flèches
✅ Icônes en dégradé dans coins supérieurs
✅ Sous-titres en gris neutre

🎯 3. PAGES À MODERNISER (PRIORITÉS)
Phase 1 - Dashboards ⭐⭐⭐

Dashboard Admin : Vue d'ensemble + stats générales
Dashboard Commercial : Pipeline devis + chantiers assignés
Dashboard Client : Projets en cours + communication

Phase 2 - Modules Métier ⭐⭐

Liste Chantiers : Table moderne + filtres visuels
Détail Chantier : Timeline étapes + documents
Création/Édition Devis : Formulaire moderne step-by-step

Phase 3 - Administration ⭐

Gestion Utilisateurs : CRUD avec rôles visuels
Paramètres : Interface de configuration moderne
Rapports : Charts modernes + exports


🔧 4. SPÉCIFICATIONS TECHNIQUES
Framework & Outils

CSS : Tailwind CSS avec classes personnalisées
JavaScript : Alpine.js pour interactions
Icônes : Heroicons (comme référence)
Animations : Transitions CSS + keyframes simples

Responsive Design
cssMobile : Sidebar collapse + header adapté
Tablet : Réduction espaces + grid 2 colonnes
Desktop : Layout complet comme référence
Performance

Lazy loading : Images et composants lourds
CSS optimisé : Classes utilitaires + composants
Transitions : Durées courtes (200-300ms)


📱 5. INTERACTIONS & ANIMATIONS
Micro-animations Obligatoires
css✅ Hover cards : translateY(-2px) + shadow enhanced
✅ Boutons : scale(1.05) + couleur de fond
✅ Stats : compteurs animés au chargement
✅ Navigation : bordures colorées animées
✅ Loading : pulse sur éléments en attente
États Visuels
css✅ Focus : ring indigo + outline
✅ Disabled : opacity 50% + cursor not-allowed  
✅ Loading : skeleton + pulse animation
✅ Empty state : illustration + CTA coloré

🎨 6. GUIDELINES DE DÉVELOPPEMENT
Structure HTML
html✅ Sémantique claire (header, nav, main, aside)
✅ Classes utilitaires Tailwind
✅ Composants Blade réutilisables
✅ Accessibilité (ARIA labels, alt text)
CSS Organisation
css✅ Variables CSS pour couleurs
✅ Classes composants dans @layer
✅ Utilities personnalisées
✅ Responsive mobile-first
JavaScript Patterns
javascript✅ Alpine.js pour state management
✅ Fetch API pour données async
✅ Event delegation pour performance
✅ Error handling avec notifications

🚀 7. CHECKLIST DE VALIDATION
Chaque page doit respecter :

 Palette couleurs identique à la référence
 Spacing harmonieux (multiples de 8px)
 Ombres cohérentes (soft/medium selon contexte)
 Animations fluides (hover, transitions)
 Responsive parfait (mobile → desktop)
 Accessibilité (contraste, navigation clavier)
 Performance (< 3s chargement initial)


📋 8. LIVRABLES ATTENDUS
Pour chaque page :

Fichier Blade (.blade.php) avec structure
Classes CSS personnalisées si nécessaire
JavaScript Alpine.js pour interactions
Documentation des composants réutilisables

Assets communs :

Design System complet (variables, classes)
Composants Blade réutilisables
Guide développeur avec exemples


🎯 RÉSULTAT FINAL : Une interface qui reproduit exactement l'esthétique moderne de votre référence, adaptée au métier BTP avec vos données métier.
Cette feuille de route est-elle claire pour vos designers ?