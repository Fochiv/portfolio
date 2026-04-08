# Portfolio Ben FOCH

Portfolio professionnel complet avec interface d'administration.

## Architecture

- **Langage** : PHP (serveur built-in PHP 8.3)
- **Base de données** : SQLite (via PDO) — `database.sqlite`
- **Frontend** : HTML, CSS custom, JavaScript vanilla, Font Awesome, Google Fonts (Inter)
- **Port** : 5000

## Fichiers principaux

| Fichier | Rôle |
|---|---|
| `index.php` | Page portfolio publique |
| `admin.php` | Interface d'administration (login + dashboard) |
| `api.php` | API REST (JSON) pour toutes les opérations AJAX |
| `config.php` | Configuration DB + fonctions utilitaires |
| `db_init.php` | Initialisation et seed de la base de données |
| `assets/css/style.css` | Styles globaux (thème sombre/clair) |
| `assets/js/main.js` | JavaScript portfolio (typing, filtres, i18n, scroll) |
| `database.sqlite` | Base de données SQLite (auto-créée) |

## Dossiers

- `assets/img/` — Images statiques (logo, projets)
- `uploads/profiles/` — Photos de profil uploadées
- `uploads/cvs/` — Fichiers CV uploadés
- `uploads/projects/` — Images projets uploadées

## Admin

- **URL** : `/admin.php`
- **Email** : `aldofoch@gmail.com`
- **Mot de passe par défaut** : `Admin@2026`

## Fonctionnalités

### Portfolio public
- Dark/Light mode avec bouton switch
- Langue FR/EN avec sélecteur
- Effet de typing animé dans le hero
- Badge de disponibilité dynamique (contrôlé depuis l'admin)
- Téléchargement du CV (géré depuis l'admin)
- Sections : Hero, À propos, Compétences, Portfolio, Services, Expérience, Témoignages, Contact
- Filtres de projets par catégorie
- Carrousel de témoignages défilant
- Formulaire de contact (messages stockés en DB)
- Responsive mobile/tablette/desktop
- Animations au scroll

### Admin
- Login sécurisé (hash bcrypt)
- Upload photo de profil
- Toggle disponibilité (temps réel)
- Upload CV PDF
- CRUD projets (nom, catégorie, description, technologies, image, lien démo, statut)
- CRUD compétences par catégorie
- Gestion des messages (lecture, suppression)
- Paramètres de contact
- Changement de mot de passe

## Démarrage

```bash
php -S 0.0.0.0:5000 -t .
```
