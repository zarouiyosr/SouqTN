# Guide de déploiement — SouqTN

Le projet est en **PHP + MySQL**. Il a besoin d'un hébergement qui
supporte ces deux technologies (un simple hébergeur de fichiers
statiques ne suffit pas).

---

## OPTION A — Hébergement mutualisé gratuit (recommandé projet école)

Exemples : **InfinityFree**, **AwardSpace**, **000webhost**.
Ils offrent PHP + MySQL + phpMyAdmin gratuitement.

### Étapes (exemple type InfinityFree)

**1. Créer un compte et un hébergement**
- S'inscrire sur le service choisi
- Créer un nouvel hébergement → vous obtenez un domaine gratuit
  (ex. `souqtn.infinityfreeapp.com`)

**2. Créer la base de données**
- Dans le panneau → section MySQL → créer une base
- Noter : nom de la base, utilisateur, mot de passe, **hôte**
  (souvent différent de `localhost`, ex. `sqlXXX.infinityfree.com`)

**3. Adapter la configuration**

Modifier `config.php` ET `core/Database.php` avec les identifiants
fournis par l'hébergeur :

```php
// Exemple — valeurs données par votre hébergeur
$host     = "sqlXXX.epizy.com";   // PAS localhost !
$username = "epiz_12345678";
$pass     = "votreMotDePasse";
$dbname   = "epiz_12345678_souqtn";
// retirer le port 3306 si l'hébergeur ne l'exige pas
```

> ⚠️ Si le dépôt est public sur GitHub, ne committez PAS ces vrais
> identifiants. Utilisez un `config.php` local non versionné.

**4. Importer la base**
- Ouvrir le phpMyAdmin de l'hébergeur
- Importer le fichier `database.sql`

**5. Uploader les fichiers**
- Via le gestionnaire de fichiers de l'hébergeur OU un client FTP
  (FileZilla)
- Placer le contenu du projet dans le dossier racine web
  (souvent `htdocs/` ou `public_html/`)

**6. Adapter le chemin de base**

Le projet utilise `/SouqTN/public` comme base. En ligne, la racine
est différente. Deux solutions :

- **Simple** : uploader uniquement le **contenu de `public/`** à la
  racine web, et remonter les autres dossiers (`app/`, `core/`...)
  un niveau au-dessus de la racine web.
- **Ou** : adapter la base dans `core/Router.php` et
  `public/index.php` (remplacer `/SouqTN/public` par `''` ou le bon
  chemin), ainsi que les liens `/SouqTN/public/...` dans les vues.

**7. Créer le compte admin**
- Ouvrir `votre-domaine/setup_admin.php` une fois
- Supprimer ensuite ce fichier

**8. Tester**
- Ouvrir votre domaine → la boutique doit s'afficher

---

## OPTION B — VPS / hébergement payant (pro)

Exemples : DigitalOcean, OVH, Hostinger, Contabo.

1. Louer un VPS (Ubuntu) ou un hébergement cPanel
2. Installer la pile LAMP :
   ```bash
   sudo apt update
   sudo apt install apache2 php php-mysql mysql-server -y
   ```
3. Copier le projet dans `/var/www/html/SouqTN`
4. Créer la base : `mysql -u root -p < database.sql`
5. Adapter `config.php` / `core/Database.php`
6. Activer `mod_rewrite` : `sudo a2enmod rewrite && sudo systemctl restart apache2`
7. Configurer un VirtualHost pointant sur le dossier `public/`
8. (Recommandé) Certificat HTTPS gratuit avec Let's Encrypt / Certbot

---

## OPTION C — Plateforme cloud depuis GitHub

Exemples : Railway, Render, Fly.io (souvent freemium).

1. Pousser le projet sur GitHub (déjà fait — voir
   `docs/PUBLIER_SUR_GITHUB.md`)
2. Connecter le dépôt à la plateforme
3. Ajouter une base MySQL (service managé de la plateforme)
4. Configurer les variables d'environnement (host, user, pass, db)
5. Adapter `config.php` pour lire ces variables :
   ```php
   $host = getenv('DB_HOST') ?: 'localhost';
   $username = getenv('DB_USER') ?: 'root';
   $pass = getenv('DB_PASS') ?: '';
   $dbname = getenv('DB_NAME') ?: 'souqtn';
   ```
6. Déployer (build automatique à chaque push)

---

## Checklist avant tout déploiement

- [ ] `config.php` et `core/Database.php` adaptés au serveur distant
- [ ] Base importée (`database.sql`) sur le serveur
- [ ] Chemin de base ajusté (`/SouqTN/public` → racine du domaine)
- [ ] `setup_admin.php` exécuté **puis supprimé**
- [ ] Identifiants BDD réels NON présents dans le dépôt public
- [ ] `mod_rewrite` actif (pour le `.htaccess`)
- [ ] Testé : boutique, connexion, panier, commande, dashboard

## Problème fréquent : les chemins absolus

Le projet utilise des liens comme `/SouqTN/public/login`. En
production, si le site est à la racine du domaine, ces liens doivent
devenir `/login`. Faites un rechercher-remplacer global de
`/SouqTN/public` selon votre configuration d'hébergement, ou gardez
un sous-dossier `/SouqTN/public` identique au local pour éviter toute
modification.
