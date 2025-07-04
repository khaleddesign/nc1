# 🏗️ N-C - Système de Gestion de Chantiers, Devis et Factures

[![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.3.5-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 📋 Table des Matières

- [Description](#-description)
- [Fonctionnalités](#-fonctionnalités)
- [Architecture Technique](#-architecture-technique)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure du Projet](#-structure-du-projet)
- [Base de Données](#-base-de-données)
- [API Endpoints](#-api-endpoints)
- [Sécurité](#-sécurité)
- [Tests](#-tests)
- [Déploiement](#-déploiement)
- [Contribution](#-contribution)
- [Support](#-support)

## 🎯 Description

**N-C** est un système de gestion complet développé en Laravel 12 pour les entreprises du BTP (Bâtiment et Travaux Publics). Il permet la gestion intégrée des chantiers, devis, factures, clients, commerciaux et artisans avec un système de rôles avancé.

### 🎨 Interface Utilisateur
- **Design moderne** avec Tailwind CSS 3.3.5
- **Interface responsive** adaptée mobile/desktop
- **Composants Alpine.js** pour l'interactivité
- **Icônes Heroicons** pour une UX optimale

## ✨ Fonctionnalités

### 👥 Gestion des Utilisateurs
- **4 rôles distincts** : Admin, Commercial, Client, Artisan
- **Système d'authentification** Laravel Sanctum
- **Gestion des permissions** par rôle
- **Profils utilisateurs** personnalisables
- **Préférences email** configurables

### 🏗️ Gestion des Chantiers
- **Création et suivi** des chantiers
- **Étapes de progression** avec pourcentages
- **Documents attachés** (plans, photos, etc.)
- **Commentaires** et communication interne
- **Calendrier** des chantiers
- **Statuts** : Planifié, En cours, Terminé

### 📋 Gestion des Devis
- **Devis prospects** (sans chantier associé)
- **Devis chantiers** (liés à un chantier)
- **Versions de négociation** avec historique
- **Conversion en chantier** automatique
- **Statuts** : Brouillon, Envoyé, Accepté, Refusé
- **Génération PDF** automatique
- **Conformité électronique** (optionnel)

### 🧾 Gestion des Factures
- **Création depuis devis** ou manuelle
- **Lignes de facturation** détaillées
- **Calculs automatiques** (HT, TVA, TTC)
- **Gestion des paiements** partiels
- **Relances automatiques**
- **Statuts** : Brouillon, Envoyée, Payée, Annulée
- **Export PDF** professionnel

### 💰 Gestion Financière
- **Suivi des paiements** par facture
- **Rapports financiers** détaillés
- **Chiffre d'affaires** par période
- **Analyses de performance** commerciale
- **Santé financière** de l'entreprise

### 📱 Messagerie et Notifications
- **Système de messagerie** interne
- **Notifications en temps réel**
- **Emails automatiques** (devis, factures)
- **Historique des communications**

### 📊 Rapports et Analytics
- **Dashboard administrateur** complet
- **Statistiques commerciales**
- **Rapports de performance**
- **Export PDF** des rapports
- **Graphiques interactifs**

## 🏗️ Architecture Technique

### Backend
- **Framework** : Laravel 12.0
- **PHP** : 8.2+
- **Base de données** : SQLite (développement) / MySQL/PostgreSQL (production)
- **Authentification** : Laravel Sanctum
- **Cache** : Redis (optionnel)
- **Queue** : Database/Redis

### Frontend
- **CSS Framework** : Tailwind CSS 3.3.5
- **JavaScript** : Alpine.js 3.13.3
- **Build Tool** : Vite 4.0
- **Icônes** : Heroicons 2.0.18
- **Charts** : Chart.js (via CDN)

### Services
- **Génération PDF** : DomPDF 3.1
- **Traitement d'images** : Intervention Image 3.11
- **API** : RESTful avec Sanctum

## 📋 Prérequis

### Système
- **PHP** : 8.2 ou supérieur
- **Composer** : 2.0 ou supérieur
- **Node.js** : 18.0 ou supérieur
- **npm** : 9.0 ou supérieur

### Extensions PHP Requises
```bash
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml)"
```

### Base de Données
- **SQLite** (développement) - inclus
- **MySQL** 8.0+ (production)
- **PostgreSQL** 13+ (production)

## 🚀 Installation

### 1. Cloner le Repository
```bash
git clone https://github.com/khaleddesign/nc1.git
cd nc1
```

### 2. Installer les Dépendances
```bash
# Dépendances PHP
composer install

# Dépendances Node.js
npm install
```

### 3. Configuration de l'Environnement
```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configuration de la Base de Données
```bash
# Créer la base SQLite (développement)
touch database/database.sqlite

# Ou configurer MySQL/PostgreSQL dans .env
```

### 5. Migrations et Seeders
```bash
# Exécuter les migrations
php artisan migrate

# Charger les données de test (optionnel)
php artisan db:seed
```

### 6. Compilation des Assets
```bash
# Développement
npm run dev

# Production
npm run build
```

### 7. Démarrer le Serveur
```bash
# Serveur de développement
php artisan serve

# Ou utiliser le script complet
composer run dev
```

## ⚙️ Configuration

### Variables d'Environnement (.env)

```env
# Application
APP_NAME="N-C Gestion"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Mail (pour les notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Facturation électronique (optionnel)
FACTURATION_ELECTRONIQUE_ACTIVE=false
FACTURATION_ELECTRONIQUE_CERTIFICAT_PATH=
FACTURATION_ELECTRONIQUE_PRIVATE_KEY_PATH=
```

### Configuration des Rôles

Les rôles sont définis dans `app/Models/User.php` :

```php
const ROLE_ADMIN = 'admin';
const ROLE_COMMERCIAL = 'commercial';
const ROLE_CLIENT = 'client';
const ROLE_ARTISAN = 'artisan';
```

## 📁 Structure du Projet

```
n-c/
├── app/
│   ├── Console/Commands/          # Commandes Artisan
│   ├── Events/                    # Événements
│   ├── Http/
│   │   ├── Controllers/           # Contrôleurs
│   │   ├── Middleware/            # Middleware personnalisés
│   │   ├── Requests/              # Validation des formulaires
│   │   └── Resources/             # API Resources
│   ├── Jobs/                      # Tâches en arrière-plan
│   ├── Listeners/                 # Écouteurs d'événements
│   ├── Mail/                      # Templates d'emails
│   ├── Models/                    # Modèles Eloquent
│   ├── Policies/                  # Politiques d'autorisation
│   ├── Providers/                 # Service Providers
│   └── Services/                  # Services métier
├── config/                        # Configuration
├── database/
│   ├── factories/                 # Factories pour les tests
│   ├── migrations/                # Migrations de base de données
│   └── seeders/                   # Seeders
├── public/                        # Fichiers publics
├── resources/
│   ├── css/                       # Styles CSS
│   ├── js/                        # JavaScript
│   ├── sass/                      # SASS (si utilisé)
│   └── views/                     # Vues Blade
├── routes/                        # Définition des routes
├── storage/                       # Stockage des fichiers
└── tests/                         # Tests automatisés
```

## 🗄️ Base de Données

### Tables Principales

#### Users
```sql
- id (bigint, primary key)
- name (varchar)
- email (varchar, unique)
- password (varchar)
- role (enum: admin, commercial, client, artisan)
- active (boolean)
- email_preferences (json)
- created_at, updated_at
```

#### Chantiers
```sql
- id (bigint, primary key)
- titre (varchar)
- description (text)
- client_id (bigint, foreign key)
- commercial_id (bigint, foreign key)
- statut (enum: planifie, en_cours, termine)
- date_debut (date)
- date_fin_prevue (date)
- budget (decimal)
- notes (text)
- hidden_for_commercial (boolean)
- created_at, updated_at
```

#### Devis
```sql
- id (bigint, primary key)
- numero (varchar, unique)
- titre (varchar)
- chantier_id (bigint, foreign key, nullable)
- commercial_id (bigint, foreign key)
- client_info (json)
- date_emission (date)
- date_validite (date)
- montant_ht (decimal)
- montant_tva (decimal)
- montant_ttc (decimal)
- statut (enum: brouillon, envoye, accepte, refuse)
- facture_id (bigint, foreign key, nullable)
- facturation_electronique (json)
- created_at, updated_at
```

#### Factures
```sql
- id (bigint, primary key)
- numero (varchar, unique)
- titre (varchar)
- chantier_id (bigint, foreign key)
- commercial_id (bigint, foreign key)
- client_info (json)
- date_emission (date)
- date_echeance (date)
- montant_ht (decimal)
- montant_tva (decimal)
- montant_ttc (decimal)
- montant_paye (decimal)
- montant_restant (decimal)
- statut (enum: brouillon, envoyee, payee, annulee)
- facturation_electronique (json)
- created_at, updated_at
```

#### Lignes
```sql
- id (bigint, primary key)
- ligneable_type (varchar) # Polymorphic
- ligneable_id (bigint)    # Polymorphic
- ordre (integer)
- designation (varchar)
- description (text)
- unite (varchar)
- quantite (decimal)
- prix_unitaire_ht (decimal)
- taux_tva (decimal)
- montant_ht (decimal)
- montant_tva (decimal)
- montant_ttc (decimal)
- remise_pourcentage (decimal)
- remise_montant (decimal)
- categorie (varchar)
- created_at, updated_at
```

### Relations Principales

```php
// User (Commercial) -> Chantiers
User::hasMany(Chantier::class, 'commercial_id');

// User (Client) -> Chantiers
User::hasMany(Chantier::class, 'client_id');

// Chantier -> Devis
Chantier::hasMany(Devis::class);

// Chantier -> Factures
Chantier::hasMany(Facture::class);

// Devis -> Lignes (Polymorphic)
Devis::morphMany(Ligne::class, 'ligneable');

// Facture -> Lignes (Polymorphic)
Facture::morphMany(Ligne::class, 'ligneable');

// Devis -> Facture
Devis::belongsTo(Facture::class);
```

## 🔌 API Endpoints

### Authentification
```http
POST /api/login
POST /api/logout
GET  /api/user
```

### Chantiers
```http
GET    /api/chantiers
POST   /api/chantiers
GET    /api/chantiers/{id}
PUT    /api/chantiers/{id}
DELETE /api/chantiers/{id}
GET    /api/chantiers/{id}/avancement
```

### Devis
```http
GET    /api/devis
POST   /api/devis
GET    /api/devis/{id}
PUT    /api/devis/{id}
DELETE /api/devis/{id}
POST   /api/devis/{id}/convert-to-chantier
```

### Factures
```http
GET    /api/factures
POST   /api/factures
GET    /api/factures/{id}
PUT    /api/factures/{id}
DELETE /api/factures/{id}
POST   /api/factures/{id}/paiement
```

### Notifications
```http
GET  /api/notifications
POST /api/notifications/{id}/read
GET  /api/notifications/count
```

## 🔒 Sécurité

### Authentification
- **Laravel Sanctum** pour l'API
- **Sessions** pour l'interface web
- **Middleware d'authentification** sur toutes les routes protégées

### Autorisation
- **Policies** pour chaque modèle
- **Gates** pour les permissions globales
- **Middleware de rôles** personnalisé

### Validation
- **Form Requests** pour la validation des données
- **Sanitisation** automatique des entrées
- **Protection CSRF** activée

### Base de Données
- **Préparation des requêtes** (protection SQL injection)
- **Chiffrement** des données sensibles
- **Backup** automatique recommandé

## 🧪 Tests

### Exécution des Tests
```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=ChantierTest

# Tests avec couverture
php artisan test --coverage
```

### Structure des Tests
```
tests/
├── Feature/           # Tests d'intégration
│   ├── ApiTest.php
│   ├── AuthenticationBtpTest.php
│   ├── ChantierCrudBtpTest.php
│   ├── DevisCompletBtpTest.php
│   └── FactureWorkflowTest.php
└── Unit/              # Tests unitaires
    ├── ChantierTest.php
    ├── DevisTest.php
    ├── FactureTest.php
    └── Services/
        └── PdfServiceTest.php
```

### Tests Disponibles
- **Authentification** et gestion des rôles
- **CRUD** des chantiers, devis, factures
- **Workflows** complets (devis → facture → paiement)
- **API** endpoints
- **Services** (PDF, notifications)

## 🚀 Déploiement

### Production
```bash
# Optimisations
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Base de données
php artisan migrate --force
```

### Environnement de Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Cache et sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nc_production
DB_USERNAME=nc_user
DB_PASSWORD=secure_password
```

### Serveur Web
- **Nginx** ou **Apache** configuré
- **PHP-FPM** pour les performances
- **SSL/TLS** obligatoire
- **Backup** automatique de la base de données

## 🤝 Contribution

### Prérequis
- Fork du repository
- Branche feature : `git checkout -b feature/nouvelle-fonctionnalite`
- Tests passants
- Code style respecté (Laravel Pint)

### Processus
1. **Fork** le projet
2. **Créer** une branche feature
3. **Développer** avec tests
4. **Commit** avec messages clairs
5. **Push** vers votre fork
6. **Pull Request** avec description détaillée

### Standards de Code
```bash
# Formatage automatique
./vendor/bin/pint

# Analyse statique
./vendor/bin/phpstan analyse

# Tests avant commit
php artisan test
```

## 📞 Support

### Documentation
- **README** : Ce fichier
- **Code** : Commentaires dans le code
- **Tests** : Exemples d'utilisation

### Contact
- **Issues** : [GitHub Issues](https://github.com/khaleddesign/nc1/issues)
- **Discussions** : [GitHub Discussions](https://github.com/khaleddesign/nc1/discussions)

### Logs
```bash
# Logs d'application
tail -f storage/logs/laravel.log

# Logs en temps réel
php artisan pail

# Debug en développement
php artisan debugbar:clear
```

## 📄 Licence

Ce projet est sous licence **MIT**. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🙏 Remerciements

- **Laravel** pour le framework exceptionnel
- **Tailwind CSS** pour le design system
- **Alpine.js** pour l'interactivité
- **Heroicons** pour les icônes
- **DomPDF** pour la génération PDF

---

**N-C** - Système de Gestion Complet pour BTP 🏗️

*Développé avec ❤️ en Laravel 12*
