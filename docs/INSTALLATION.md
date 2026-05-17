# Guide d'installation — SouqTN

## Prérequis

- **XAMPP** (Apache + PHP 8+ + MySQL) — ou WAMP/MAMP
- Un navigateur web
- Git (pour cloner le dépôt)

## Étape 1 — Cloner le projet

```bash
cd C:\xampp\htdocs
git clone <URL_DU_DEPOT_GITHUB> SouqTN
```

Le chemin final doit être : `C:\xampp\htdocs\SouqTN`

## Étape 2 — Démarrer les services

Dans le panneau de contrôle XAMPP, démarrez **Apache** et **MySQL**.

## Étape 3 — Créer la base de données

1. Ouvrir `http://localhost/phpmyadmin`
2. Onglet **Importer** → choisir `database.sql` → **Exécuter**

Cela crée la base `souqtn` avec toutes les tables (users, produits,
cart, favoris, orders, order_items, categories) et 12 produits de
démonstration.

## Étape 4 — Vérifier la configuration

Ouvrir `config.php` et `core/Database.php`. Par défaut :
- hôte : `localhost`
- utilisateur : `root`
- mot de passe : *(vide)*
- port : `3306`

Si votre MySQL utilise un autre port, modifiez-le dans ces deux
fichiers.

## Étape 5 — Créer le compte admin

Ouvrir **une seule fois** :

```
http://localhost/SouqTN/public/setup_admin.php
```

Un message vert confirme la création. **Supprimez ensuite ce fichier**
pour la sécurité.

## Étape 6 — Lancer le site

```
http://localhost/SouqTN/public/
```

## Comptes de test

- **Admin** : `admin@souqtn.tn` / `admin123`
  → redirigé vers le dashboard admin
- **Client** : créez-en un via la page d'inscription
  → redirigé vers la boutique

## Résolution de problèmes

| Erreur | Cause | Solution |
|--------|-------|----------|
| `[2002] refused` | MySQL pas démarré ou mauvais port | Démarrer MySQL / corriger le port |
| `Unknown column 'statut'` | BDD pas à jour | Réimporter `database.sql` |
| `404 + liste de routes` | URL incorrecte | Utiliser `/SouqTN/public/` |
| Connexion admin échoue | Hash invalide | Relancer `setup_admin.php` |
| Page blanche / 500 | `mod_rewrite` désactivé | Activer dans Apache |
