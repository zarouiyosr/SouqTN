# SouqTN — Répartition du travail (4 personnes, équilibrée USER + ADMIN)

Principe : **chaque personne travaille à la fois sur une fonctionnalité
côté CLIENT (user) et une fonctionnalité côté ADMIN.** Le travail est
réparti pour être équilibré et limiter les conflits Git.

Fichiers partagés (à coordonner, ne pas modifier seul·e) :
`config.php`, `core/Database.php`, `core/Router.php`,
`public/index.php`, `database.sql`.
→ Désignez **un·e responsable intégration** qui centralise les routes
et le schéma BDD.

---

## 👤 PERSONNE 1
### 🟢 Côté USER : Inscription & Connexion
### 🔵 Côté ADMIN : Compte admin & Sécurité des accès

**Fichiers possédés**
- `app/controllers/AuthController.php`
- `app/views/auth/login.php`
- `app/views/auth/register.php`
- `app/models/User.php`
- `public/setup_admin.php`

**Tâches USER**
- Page d'inscription avec validation (email, mot de passe >= 8 car.).
- Page de connexion + messages d'erreur.
- Deconnexion (destruction de session).

**Tâches ADMIN**
- Script `setup_admin.php` (creer/reparer le compte admin).
- Redirection selon role : admin -> dashboard, client -> boutique.
- Protection : un non-admin ne peut pas acceder aux pages admin.
- Hachage bcrypt des mots de passe.

---

## 🛍️ PERSONNE 2
### 🟢 Côté USER : Catalogue & Boutique
### 🔵 Côté ADMIN : CRUD Produits & Catégories

**Fichiers possédés**
- `app/controllers/ShopController.php`
- `app/controllers/ProductController.php`
- `app/models/Produit.php`, `app/models/Category.php`
- `app/views/shop/index.php` (partie HTML/CSS/affichage produits)
- `app/views/shop/order.php`
- `app/views/dashboard/products_crud.php`
- `crud/Crudproduit.php`, `crud/Crudcat.php`
- `public/css/style1.css`

**Tâches USER**
- Affichage du catalogue depuis la BDD (filtres, recherche, tri).
- Fiche produit, recommandations.

**Tâches ADMIN**
- CRUD produits (ajouter / modifier / supprimer) + validation.
- CRUD categories.
- Synchronisation : produit modifie en admin -> visible en boutique.

---

## 🛒 PERSONNE 3
### 🟢 Côté USER : Panier, Favoris & Commande
### 🔵 Côté ADMIN : Tableau de bord & Statistiques

**Fichiers possédés**
- `app/controllers/CartController.php`
- `app/models/Cart.php`, `app/models/Favori.php`
- `app/models/Order.php` (partie client : createFromCart, getByUser,
  getItems, getStats)
- `app/models/Stats.php`
- `app/views/dashboard/admin.php` (structure + onglet dashboard)
- `public/css/dashboard.css`
- Sections JS panier/favoris/checkout de `shop/index.php`
  (coordonner avec Personne 2)

**Tâches USER**
- Panier persistant (ajout/retrait/quantite).
- Favoris persistants + panneau favoris.
- Bouton « Passer la commande » (cree la commande, vide le panier).

**Tâches ADMIN**
- Tableau de bord admin : statistiques (users, produits, ventes).
- Graphiques + derniers utilisateurs/produits.
- Structure de la page `admin.php` (sidebar, onglets).

---

## 📊 PERSONNE 4
### 🟢 Côté USER : Espace client, Suivi & Profil
### 🔵 Côté ADMIN : Gestion des livraisons & CRUD Users

**Fichiers possédés**
- `app/controllers/DashboardController.php`
- `app/controllers/ProfileController.php`
- `app/controllers/UserController.php`
- `app/views/dashboard/client.php`
- `app/views/dashboard/tracking.php`
- `app/views/dashboard/profile.php`
- `app/views/dashboard/deliveries.php`
- `app/views/dashboard/users_crud.php`
- `app/models/Order.php` (partie admin : getAllOrders, setStatut,
  getGlobalStats — coordonner avec Personne 3)
- `crud/Cruduser.php`
- `public/css/dashboard-client.css`

**Tâches USER**
- Espace client : commandes, livraisons en cours / recues.
- Page « Suivi de livraison » (timeline).
- Page « Mon profil » : voir + modifier nom/email/mot de passe.

**Tâches ADMIN**
- Onglet « Livraisons » : lister toutes les commandes, changer le
  statut (en cours <-> livree).
- CRUD utilisateurs (`users_crud.php`).

---

## Tableau récapitulatif équilibré

| Personne | USER                          | ADMIN                            |
|----------|-------------------------------|----------------------------------|
| 1        | Inscription & connexion       | Compte admin & securite          |
| 2        | Catalogue & boutique          | CRUD produits & categories       |
| 3        | Panier, favoris & commande    | Dashboard & statistiques         |
| 4        | Espace client, suivi & profil | Gestion livraisons & CRUD users  |

Chaque personne livre **1 fonctionnalite client + 1 fonctionnalite
admin**, de poids comparable.

## Coordination

- `Order.php` partage Personne 3 (client) / Personne 4 (admin) :
  separez clairement les methodes, communiquez avant chaque push.
- `shop/index.php` partage Personne 2 (HTML/CSS) / Personne 3 (JS
  panier) : convenez d'une zone par personne.
- Routes (`public/index.php`) et schema (`database.sql`) : le·la
  responsable integration les met a jour selon les besoins transmis.
- Une branche Git par personne, Pull Request en fin de module.
