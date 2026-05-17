# SouqTN — Git par personne + Déploiement (étape par étape)

Ce guide donne, **pour CHAQUE personne**, ses étapes Git
individuelles. Puis le déploiement à la fin (fait par 1 personne).

═══════════════════════════════════════════════════════════════
# ÉTAPE 0 — UNE SEULE PERSONNE (le chef de projet) prépare le dépôt
═══════════════════════════════════════════════════════════════

Une seule personne fait ceci (par exemple Personne 1) :

### 0.1 — Installer Git
- https://git-scm.com/download/win → installer (Next partout)

### 0.2 — Configurer Git
```
git config --global user.name "Nom Chef"
git config --global user.email "chef@email.com"
```

### 0.3 — Créer le dépôt sur GitHub
- github.com → New repository → nom : `SouqTN`
- Public, SANS README, SANS .gitignore
- Create repository → copier l'URL
  `https://github.com/CHEF/SouqTN.git`

### 0.4 — Envoyer le projet de base
Ouvrir `cmd` dans `D:\xampp\htdocs\SouqTN` :
```
git init
git add .
git commit -m "chore: projet de base SouqTN"
git branch -M main
git remote add origin https://github.com/CHEF/SouqTN.git
git push -u origin main
```

### 0.5 — Créer les 4 branches
```
git branch feature/p1
git branch feature/p2
git branch feature/p3
git branch feature/p4
git push origin feature/p1 feature/p2 feature/p3 feature/p4
```

### 0.6 — Donner accès aux 3 autres
- Sur GitHub : dépôt → **Settings** → **Collaborators** →
  **Add people** → entrer le pseudo GitHub de chaque membre
- Chaque membre reçoit une invitation par email → accepter

═══════════════════════════════════════════════════════════════
# 👤 PERSONNE 1 — Auth (user) + Sécurité admin
Branche : feature/p1
═══════════════════════════════════════════════════════════════

### P1.1 — Installer + configurer Git
```
git config --global user.name "Personne 1"
git config --global user.email "p1@email.com"
```

### P1.2 — Cloner le projet
Ouvrir `cmd` dans `D:\xampp\htdocs` (PAS dans un sous-dossier) :
```
git clone https://github.com/CHEF/SouqTN.git
cd SouqTN
```

### P1.3 — Aller sur SA branche
```
git checkout feature/p1
```

### P1.4 — Travailler sur SES fichiers
Fichiers de Personne 1 :
- `app/controllers/AuthController.php`
- `app/views/auth/login.php`
- `app/views/auth/register.php`
- `app/models/User.php`
- `public/setup_admin.php`

### P1.5 — Committer (chaque jour)
Après avoir modifié ses fichiers :
```
git add app/controllers/AuthController.php app/views/auth/login.php
git commit -m "feat(user): page de connexion"
git push origin feature/p1
```
(adapter les fichiers/message selon le travail du jour —
voir docs/PLAN_COMMITS_GIT.md)

### P1.6 — Récupérer le travail des autres (régulièrement)
```
git checkout main
git pull
git checkout feature/p1
git merge main
```

### P1.7 — Fin de module : Pull Request
- Sur GitHub : onglet **Pull requests** → **New pull request**
- base: `main` ← compare: `feature/p1`
- **Create pull request** → un autre membre relit → **Merge**

═══════════════════════════════════════════════════════════════
# 🛍️ PERSONNE 2 — Boutique (user) + CRUD produits admin
Branche : feature/p2
═══════════════════════════════════════════════════════════════

### P2.1 — Configurer Git
```
git config --global user.name "Personne 2"
git config --global user.email "p2@email.com"
```

### P2.2 — Cloner
```
cd D:\xampp\htdocs
git clone https://github.com/CHEF/SouqTN.git
cd SouqTN
```

### P2.3 — Sa branche
```
git checkout feature/p2
```

### P2.4 — Ses fichiers
- `app/controllers/ShopController.php`
- `app/controllers/ProductController.php`
- `app/models/Produit.php`, `app/models/Category.php`
- `app/views/shop/index.php` (partie HTML/CSS)
- `app/views/shop/order.php`
- `app/views/dashboard/products_crud.php`
- `crud/Crudproduit.php`, `crud/Crudcat.php`
- `public/css/style1.css`

### P2.5 — Committer
```
git add app/models/Produit.php app/controllers/ShopController.php
git commit -m "feat(user): catalogue produits depuis la BDD"
git push origin feature/p2
```

### P2.6 — Récupérer les autres
```
git checkout main
git pull
git checkout feature/p2
git merge main
```

### P2.7 — Pull Request
GitHub → Pull requests → New → base `main` ← `feature/p2` →
relecture → Merge.

═══════════════════════════════════════════════════════════════
# 🛒 PERSONNE 3 — Panier/Commande (user) + Dashboard admin
Branche : feature/p3
═══════════════════════════════════════════════════════════════

### P3.1 — Configurer Git
```
git config --global user.name "Personne 3"
git config --global user.email "p3@email.com"
```

### P3.2 — Cloner
```
cd D:\xampp\htdocs
git clone https://github.com/CHEF/SouqTN.git
cd SouqTN
```

### P3.3 — Sa branche
```
git checkout feature/p3
```

### P3.4 — Ses fichiers
- `app/controllers/CartController.php`
- `app/models/Cart.php`, `app/models/Favori.php`
- `app/models/Order.php` (partie client)
- `app/models/Stats.php`
- `app/views/dashboard/admin.php`
- `public/css/dashboard.css`
- Sections JS panier de `app/views/shop/index.php`
  (se coordonner avec Personne 2)

### P3.5 — Committer
```
git add app/models/Cart.php app/controllers/CartController.php
git commit -m "feat(user): panier persistant en base"
git push origin feature/p3
```

### P3.6 — Récupérer les autres
```
git checkout main
git pull
git checkout feature/p3
git merge main
```

### P3.7 — Pull Request
GitHub → Pull requests → New → base `main` ← `feature/p3` →
relecture → Merge.

═══════════════════════════════════════════════════════════════
# 📊 PERSONNE 4 — Espace client (user) + Livraisons admin
Branche : feature/p4
═══════════════════════════════════════════════════════════════

### P4.1 — Configurer Git
```
git config --global user.name "Personne 4"
git config --global user.email "p4@email.com"
```

### P4.2 — Cloner
```
cd D:\xampp\htdocs
git clone https://github.com/CHEF/SouqTN.git
cd SouqTN
```

### P4.3 — Sa branche
```
git checkout feature/p4
```

### P4.4 — Ses fichiers
- `app/controllers/DashboardController.php`
- `app/controllers/ProfileController.php`
- `app/controllers/UserController.php`
- `app/views/dashboard/client.php`
- `app/views/dashboard/tracking.php`
- `app/views/dashboard/profile.php`
- `app/views/dashboard/deliveries.php`
- `app/views/dashboard/users_crud.php`
- `app/models/Order.php` (partie admin)
- `crud/Cruduser.php`
- `public/css/dashboard-client.css`

### P4.5 — Committer
```
git add app/controllers/DashboardController.php app/views/dashboard/client.php
git commit -m "feat(user): espace client commandes/livraisons"
git push origin feature/p4
```

### P4.6 — Récupérer les autres
```
git checkout main
git pull
git checkout feature/p4
git merge main
```

### P4.7 — Pull Request
GitHub → Pull requests → New → base `main` ← `feature/p4` →
relecture → Merge.

═══════════════════════════════════════════════════════════════
# RÈGLES COMMUNES (à respecter par TOUS)
═══════════════════════════════════════════════════════════════

1. Ne JAMAIS modifier les fichiers d'un·e autre.
2. Fichiers partagés (`public/index.php`, `database.sql`, `core/`) :
   seulement le chef de projet les modifie (sur `main`), les autres
   lui transmettent leurs besoins.
3. `git pull` chaque jour pour éviter les gros conflits.
4. 1 commit = 1 changement clair, message `type: description`.
5. Toujours `git push origin feature/pX` (sa propre branche).

═══════════════════════════════════════════════════════════════
# DÉPLOIEMENT (fait par UNE personne, à la fin, quand main est complet)
═══════════════════════════════════════════════════════════════

> Quand toutes les Pull Requests sont fusionnées dans `main` et que
> le site fonctionne en local, une personne déploie en ligne.
> On utilise le dossier **`SouqTN_deploy/`** (version adaptée).

### D.1 — Compte InfinityFree
- https://infinityfree.net → Sign Up → confirmer l'email

### D.2 — Créer un hébergement
- Create Account → sous-domaine gratuit (ex. `souqtn.rf.gd`)
- Noter : FTP Hostname, FTP Username, FTP Password

### D.3 — Créer la base MySQL
- Panneau → MySQL Databases → créer `souqtn`
- Noter : MySQL Hostname (PAS localhost !), Username,
  Database Name, Password

### D.4 — Adapter la config (PC)
Dans `SouqTN_deploy/`, ouvrir `config.php` ET `core/Database.php`,
mettre les infos de D.3 :
```php
$host="sqlXXX.infinityfree.com";
$username="if0_XXXXXXXX";
$pass="motdepasse";
$dbname="if0_XXXXXXXX_souqtn";
```
Retirer `;port=3306` s'il existe.

### D.5 — Préparer database.sql
Ouvrir `database.sql`, supprimer les lignes `CREATE DATABASE` et
`USE souqtn;` si présentes (la base est déjà créée).

### D.6 — Importer la base
Panneau → phpMyAdmin → sélectionner la base →
Importer → choisir `database.sql` → Exécuter.

### D.7 — Préparer index
Dans `SouqTN_deploy/public/` : supprimer `index.php`, renommer
`index_infinityfree.php` en `index.php`.

### D.8 — Installer FileZilla
- https://filezilla-project.org → installer

### D.9 — Connexion FTP
FileZilla → Hôte = FTP Hostname, Identifiant/Mot de passe FTP,
Port 21 → Connexion rapide → entrer dans `htdocs/`.

### D.10 — Uploader dans htdocs/
- TOUT le contenu de `public/` (index.php, .htaccess,
  setup_admin.php, css/, uploads/)
- Les dossiers `app/`, `core/`, `crud/`
- Le fichier `config.php`

### D.11 — Créer le compte admin
Ouvrir `http://souqtn.rf.gd/setup_admin.php` → message vert →
puis SUPPRIMER ce fichier via FileZilla.

### D.12 — Tester
`http://souqtn.rf.gd/` → boutique en ligne.
Admin : `admin@souqtn.tn` / `admin123`.

═══════════════════════════════════════════════════════════════
# AIDE RAPIDE
═══════════════════════════════════════════════════════════════

| Problème | Solution |
|----------|----------|
| `git clone` demande un mot de passe | créer un token GitHub (Settings → Developer settings → Tokens) |
| Conflit lors de `git merge` | ouvrir le fichier en conflit, garder le bon code, `git add` puis `git commit` |
| `Permission denied` au push | vérifier qu'on est invité comme collaborateur (étape 0.6) |
| Erreur BDD en ligne | host/user/pass faux (D.4) — l'hôte n'est PAS localhost |
| Page blanche en ligne | renommer `index_infinityfree.php` → `index.php` (D.7) |
| 404 partout en ligne | `.htaccess` manquant dans `htdocs/` |
