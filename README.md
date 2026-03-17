# CertiCarte

CertiCarte est une application web de gestion scolaire (élèves, classes, années scolaires, utilisateurs et documents PDF) construite avec **Laravel 12**.

## Fonctionnalités

- **Gestion des élèves**: CRUD, fiche détaillée, photo, avatar de secours, recherche/filtrage, tri, pagination.
- **Import élèves (Excel/CSV)**: import en masse, **historique des imports**, page de résultat, **export des erreurs** vers Excel, suppression en masse d’erreurs.
- **Import photos en masse**: upload multi-fichiers, association par **matricule (nom de fichier)**, remplacement si photo existante, **rapport d’import** (succès/erreurs/non trouvés).
- **Gestion des classes**: CRUD, association à une année scolaire, statistiques (compte élèves), recherche/tri/pagination, protection contre suppression si des élèves existent.
- **Années scolaires**: organisation/filtrage par année (via les classes).
- **Utilisateurs & permissions (RBAC)**: rôles `admin` et `secretary`, permissions granulaires par module/action.
- **Documents PDF**:
    - Certificat de scolarité (élève)
    - Carte d’identité (élève)
    - Liste d’appel (classe)
    - **Impression des cartes d’identité d’une classe**
- **Authentification**: connexion/déconnexion + **mot de passe oublié / réinitialisation** (email).

## Stack technique

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS + Alpine.js
- **Build**: Vite
- **Base de données**: MySQL ou SQLite
- **PDF**: DomPDF
- **Permissions**: Spatie Laravel Permission
- **Import Excel/CSV**: Maatwebsite Excel
- **Qualité**: PHPUnit, Laravel Pint

## Scripts utiles

- `composer setup`: installation + `.env` + clé + migrations + build assets
- `composer dev`: lance **serveur + queue + Vite** (concurrently)
- `composer test`: tests
- `composer lint` / `composer test:lint`: formatage Pint
- `npm run dev` / `npm run build`: assets

## Installation (rapide)

### Prérequis

- PHP 8.2+, Composer
- Node.js + npm
- MySQL (ou SQLite)

### Démarrage

```bash
composer setup
php artisan db:seed
php artisan storage:link
composer dev
```

L’application sera disponible sur `http://localhost:8000`.

## Premiers pas

- Se connecter, puis:
    - créer une année/classe (ou importer des élèves, ce qui peut créer automatiquement les entités selon le fichier),
    - gérer les élèves (photos individuelles ou import photos en masse),
    - générer les documents PDF depuis l’interface Rapports.

## Identifiants par défaut

Après `php artisan db:seed` :

- **Admin**: `admin@example.com` / `password`
- **Secrétaire**: `secretary@example.com` / `password`

À changer dès la première connexion.

## Configuration (essentiels)

Copier le fichier et générer la clé:

```bash
cp .env.example .env
php artisan key:generate
```

Variables clés à ajuster dans `.env` (voir aussi `.env.example`) :

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=certicarte
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls

SESSION_DRIVER=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

## Permissions disponibles

Le système utilise des permissions granulaires, notamment:

- `view_students`, `create_students`, `edit_students`, `delete_students`, `import_students`
- `view_classes`, `create_classes`, `edit_classes`, `delete_classes`
- `view_users`, `create_users`, `edit_users`, `delete_users`
- `generate_certificates`, `generate_cards`, `generate_attendance_lists`
