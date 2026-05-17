# SouqTN — Guide complet : Git puis Déploiement (étape par étape)

Ce guide est écrit pour débutant. Suivez les parties dans l'ordre.
**PARTIE 1** = mettre le projet sur GitHub.
**PARTIE 2** = mettre le site en ligne (InfinityFree).

═══════════════════════════════════════════════════════════════
# PARTIE 1 — METTRE LE PROJET SUR GITHUB
═══════════════════════════════════════════════════════════════

## Étape 1.1 — Installer Git (si pas déjà fait)

1. Aller sur https://git-scm.com/download/win
2. Télécharger, installer (cliquer « Next » partout, valeurs par défaut)
3. Vérifier : ouvrir l'invite de commande (touche Windows → taper
   `cmd` → Entrée) et taper :
   ```
   git --version
   ```
   Si une version s'affiche (ex. `git version 2.45`), c'est bon.

## Étape 1.2 — Configurer Git (une seule fois)

Dans l'invite de commande :

```
git config --global user.name "Votre Nom"
git config --global user.email "votre.email@exemple.com"
```

(Utilisez le même email que votre compte GitHub.)

## Étape 1.3 — Créer un compte + un dépôt sur GitHub

1. Aller sur https://github.com → **Sign up** (si pas de compte)
2. Une fois connecté, cliquer le bouton vert **New** (ou le « + » en
   haut à droite → **New repository**)
3. Remplir :
   - **Repository name** : `SouqTN`
   - **Public** (ou Private si vous voulez le garder privé)
   - ⚠️ NE PAS cocher « Add a README file »
   - NE PAS ajouter .gitignore ni licence (on les a déjà)
4. Cliquer **Create repository**
5. GitHub affiche une page avec une URL du type :
   `https://github.com/VOTRE_COMPTE/SouqTN.git`
   → **gardez cette URL**, on en a besoin.

## Étape 1.4 — Ouvrir le terminal dans le dossier du projet

1. Ouvrir l'explorateur de fichiers
2. Aller dans `D:\xampp\htdocs\SouqTN`
3. Dans la barre d'adresse de l'explorateur, taper `cmd` puis Entrée
   → une invite de commande s'ouvre **directement dans ce dossier**

Vérifier qu'on est au bon endroit :
```
cd
```
Doit afficher `D:\xampp\htdocs\SouqTN`

## Étape 1.5 — Initialiser Git et faire le premier commit

Taper ces commandes **une par une** (Entrée après chaque ligne) :

```
git init
```
> Crée un dépôt Git local (un dossier caché `.git`).

```
git add .
```
> Ajoute tous les fichiers (le `.gitignore` exclut automatiquement
> les fichiers inutiles). Le point « . » signifie « tout ».

```
git commit -m "chore: premier commit - projet SouqTN complet"
```
> Enregistre une « photo » de tous les fichiers. Le texte après
> `-m` est le message du commit.

## Étape 1.6 — Relier au dépôt GitHub

```
git branch -M main
```
> Renomme la branche principale en « main ».

```
git remote add origin https://github.com/VOTRE_COMPTE/SouqTN.git
```
> ⚠️ Remplacez `VOTRE_COMPTE` par votre nom GitHub (URL de l'étape 1.3).

```
git push -u origin main
```
> Envoie le code sur GitHub.

## Étape 1.7 — S'authentifier

Au `git push`, une fenêtre s'ouvre :
- Choisir **« Sign in with your browser »**
- Se connecter à GitHub dans le navigateur
- Autoriser

(Si on demande un mot de passe en ligne de commande : ce n'est PAS
votre mot de passe GitHub, c'est un **token**. Créez-le sur
https://github.com/settings/tokens → « Generate new token (classic) »
→ cocher « repo » → générer → copier → coller comme mot de passe.)

## Étape 1.8 — Vérifier

Recharger la page de votre dépôt sur github.com :
`https://github.com/VOTRE_COMPTE/SouqTN`
→ Tous vos fichiers doivent apparaître. **Git est fait. ✓**

## Étape 1.9 — Créer les branches de l'équipe (optionnel)

```
git branch feature/p1
git branch feature/p2
git branch feature/p3
git branch feature/p4
git push origin feature/p1 feature/p2 feature/p3 feature/p4
```

## Pour la suite (travail quotidien de l'équipe)

Chaque jour, après avoir modifié des fichiers :
```
git add .
git commit -m "feat: description de ce que j'ai fait"
git push
```

Pour récupérer le travail des autres :
```
git pull
```

═══════════════════════════════════════════════════════════════
# PARTIE 2 — DÉPLOYER LE SITE EN LIGNE (InfinityFree)
═══════════════════════════════════════════════════════════════

> Utilisez le dossier **`SouqTN_deploy/`** (version adaptée à la mise
> en ligne), PAS le dossier local `SouqTN/`.

## Étape 2.1 — Créer un compte InfinityFree

1. Aller sur https://infinityfree.net
2. Cliquer **Sign Up**, créer un compte, confirmer l'email

## Étape 2.2 — Créer un hébergement

1. Tableau de bord → **Create Account** (ou « New Account »)
2. Choisir un sous-domaine gratuit, ex. `souqtn.rf.gd`
3. Valider, attendre quelques minutes (création en cours)
4. Noter les infos FTP affichées :
   - **FTP Hostname** (ex. `ftpupload.net`)
   - **FTP Username** (ex. `if0_12345678`)
   - **FTP Password**

## Étape 2.3 — Créer la base de données

1. Dans le panneau (Control Panel) → **MySQL Databases**
2. Créer une base, ex. `souqtn`
3. **Noter précisément** (capture d'écran conseillée) :
   - **MySQL Hostname** : ex. `sql123.infinityfree.com`
     ⚠️ CE N'EST PAS « localhost »
   - **MySQL Username** : ex. `if0_12345678`
   - **Database Name** : ex. `if0_12345678_souqtn`
   - **Password** : (le mot de passe de votre compte InfinityFree)

## Étape 2.4 — Adapter la configuration (sur votre PC)

Dans le dossier `SouqTN_deploy/`, ouvrir avec le Bloc-notes :

**Fichier `config.php`** — modifier ces lignes avec les infos 2.3 :
```php
$host     = "sql123.infinityfree.com";
$username = "if0_12345678";
$pass     = "VOTRE_MOT_DE_PASSE";
$dbname   = "if0_12345678_souqtn";
```
Et supprimer `;port=3306` s'il est présent dans la ligne `new PDO(...)`.

**Fichier `core/Database.php`** — adapter la ligne `new PDO(...)`
de la même façon (host, dbname, user, pass).

Enregistrer les deux fichiers.

## Étape 2.5 — Préparer database.sql

Ouvrir `database.sql` avec le Bloc-notes. Si les premières lignes
contiennent `CREATE DATABASE` et `USE souqtn;`, **supprimez ces
2-3 lignes** (la base est déjà créée par InfinityFree avec un autre
nom). Enregistrer.

## Étape 2.6 — Importer la base

1. Panneau InfinityFree → cliquer **phpMyAdmin** (à côté de la base)
2. À gauche, cliquer sur votre base `if0_12345678_souqtn`
3. Onglet **Importer** (en haut)
4. **Choisir un fichier** → sélectionner `database.sql`
5. Bouton **Exécuter** (ou « Go ») en bas
6. Message vert = base importée ✓

## Étape 2.7 — Préparer le fichier index

Dans `SouqTN_deploy/public/`, il y a deux fichiers :
- `index.php`
- `index_infinityfree.php`

→ **Supprimez `index.php`**, puis **renommez
`index_infinityfree.php` en `index.php`**.
(Cette version a les bons chemins pour un hébergement où tout est
dans un seul dossier.)

## Étape 2.8 — Télécharger FileZilla (pour l'upload FTP)

1. Aller sur https://filezilla-project.org → **Download FileZilla
   Client**
2. Installer (valeurs par défaut)

## Étape 2.9 — Se connecter en FTP

Ouvrir FileZilla, en haut remplir :
- **Hôte** : le FTP Hostname (étape 2.2, ex. `ftpupload.net`)
- **Identifiant** : FTP Username
- **Mot de passe** : FTP Password
- **Port** : 21
- Cliquer **Connexion rapide**

À droite (serveur distant), double-cliquer pour entrer dans le
dossier **`htdocs`**.

## Étape 2.10 — Uploader les fichiers

Dans FileZilla, à gauche (votre PC), aller dans `SouqTN_deploy/`.

Glisser-déposer vers la droite (dans `htdocs/`) :

1. **TOUT le contenu du dossier `public/`** (les fichiers
   `index.php`, `.htaccess`, `setup_admin.php`, et les dossiers
   `css/`, `uploads/`) → directement dans `htdocs/`
2. Les dossiers **`app/`**, **`core/`**, **`crud/`** → dans `htdocs/`
3. Le fichier **`config.php`** → dans `htdocs/`

Résultat attendu sur le serveur (`htdocs/`) :
```
htdocs/
├── index.php
├── .htaccess
├── setup_admin.php
├── config.php
├── css/
├── uploads/
├── app/
├── core/
└── crud/
```

Attendre que tous les transferts soient finis (barre en bas).

## Étape 2.11 — Créer le compte admin

Dans le navigateur, ouvrir :
```
http://souqtn.rf.gd/setup_admin.php
```
(remplacez par votre vrai sous-domaine)

→ Message vert « Compte admin créé ».

Puis dans FileZilla, **supprimer le fichier `setup_admin.php`** du
serveur (clic droit → Supprimer) — important pour la sécurité.

## Étape 2.12 — Tester le site en ligne 🎉

Ouvrir : `http://souqtn.rf.gd/`

- La boutique s'affiche avec les produits
- Connexion : `admin@souqtn.tn` / `admin123` → dashboard admin
- Créer un compte client → tester panier, commande, suivi

**Le site est en ligne. ✓**

═══════════════════════════════════════════════════════════════
# EN CAS DE PROBLÈME
═══════════════════════════════════════════════════════════════

| Problème | Solution |
|----------|----------|
| `git: command not found` | Réinstaller Git (étape 1.1) |
| `git push` rejeté / auth | Créer un token (étape 1.7) |
| Erreur connexion BDD en ligne | Vérifier host/user/pass (étape 2.4) — l'hôte n'est PAS localhost |
| Page blanche / erreur 500 | Vérifier que `index_infinityfree.php` a bien été renommé en `index.php` (étape 2.7) |
| 404 sur tous les liens | `.htaccess` non uploadé — vérifier qu'il est dans `htdocs/` |
| Pas de style (CSS) | Le dossier `css/` doit être à la racine de `htdocs/` |
| Site lent / coupé | Normal sur hébergement gratuit |

Pour toute étape bloquante, notez le message d'erreur EXACT et
le numéro de l'étape — c'est ce qui permet de débloquer vite.
