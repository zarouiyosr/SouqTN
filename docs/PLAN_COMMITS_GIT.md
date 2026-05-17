# SouqTN — Plan de commits Git (5 jours, 4 personnes)
## Chaque personne alterne tâches USER et ADMIN

Pour chaque personne, chaque jour mélange du travail **côté client**
et **côté admin**. Messages : `type: description` (feat, fix, docs...).

## Jour 0 — Mise en place (ensemble)

```bash
git init
git add config.php core/ public/index.php public/.htaccess database.sql .gitignore README.md
git commit -m "chore: structure initiale MVC + config BDD"
git branch feature/p1 feature/p2 feature/p3 feature/p4
```

---

# 👤 PERSONNE 1 — Auth (user) + Sécurité admin
Branche : `feature/p1`

### Jour 1
```bash
git add app/models/User.php
git commit -m "feat(user): modèle User (findByEmail, create)"
```
### Jour 2
```bash
git add app/views/auth/login.php app/controllers/AuthController.php
git commit -m "feat(user): page de connexion + vérif mot de passe"
```
### Jour 3
```bash
git add app/views/auth/register.php
git commit -m "feat(user): page d'inscription + validation"
```
### Jour 4  (bascule ADMIN)
```bash
git add public/setup_admin.php
git commit -m "feat(admin): script de création du compte admin"
git add app/controllers/AuthController.php
git commit -m "feat(admin): redirection selon rôle + protection accès"
```
### Jour 5  (USER + ADMIN)
```bash
git add app/controllers/AuthController.php
git commit -m "feat(user): déconnexion et destruction de session"
git add app/models/User.php
git commit -m "fix(admin): hachage bcrypt + emailExistsExcept"
git commit -m "docs: module authentification"
```

---

# 🛍️ PERSONNE 2 — Boutique (user) + CRUD produits admin
Branche : `feature/p2`

### Jour 1
```bash
git add app/models/Produit.php app/models/Category.php
git commit -m "feat(user): modèle Produit + catégories"
```
### Jour 2
```bash
git add app/controllers/ShopController.php
git commit -m "feat(user): contrôleur boutique (produits depuis BDD)"
```
### Jour 3
```bash
git add app/views/shop/index.php public/css/style1.css
git commit -m "feat(user): page boutique (catalogue, filtres, tri)"
```
### Jour 4  (bascule ADMIN)
```bash
git add app/controllers/ProductController.php app/views/dashboard/products_crud.php
git commit -m "feat(admin): CRUD produits (ajout/édition/suppression)"
git commit -m "fix(admin): validation prix et stock"
```
### Jour 5  (USER + ADMIN)
```bash
git add crud/Crudproduit.php crud/Crudcat.php
git commit -m "feat(admin): CRUD catégories standalone"
git add app/views/shop/index.php
git commit -m "fix(user): catalogue synchronisé avec la BDD"
git commit -m "docs: module boutique"
```

---

# 🛒 PERSONNE 3 — Panier/Commande (user) + Dashboard admin
Branche : `feature/p3`

### Jour 1
```bash
git add app/models/Cart.php
git commit -m "feat(user): modèle Cart (add, remove, setQty)"
```
### Jour 2
```bash
git add app/models/Favori.php app/controllers/CartController.php
git commit -m "feat(user): favoris + endpoints AJAX panier"
```
### Jour 3
```bash
git add app/models/Order.php
git commit -m "feat(user): commande depuis le panier + checkout"
```
### Jour 4  (bascule ADMIN)
```bash
git add app/models/Stats.php
git commit -m "feat(admin): modèle Stats (users, produits, ventes)"
git add app/views/dashboard/admin.php public/css/dashboard.css
git commit -m "feat(admin): structure dashboard + statistiques"
```
### Jour 5  (USER + ADMIN)
```bash
git add app/views/shop/index.php
git commit -m "feat(user): bouton 'Passer la commande' relié au serveur"
git add app/views/dashboard/admin.php
git commit -m "feat(admin): graphiques et derniers utilisateurs"
git commit -m "docs: module panier & dashboard"
```

---

# 📊 PERSONNE 4 — Espace client (user) + Livraisons admin
Branche : `feature/p4`

### Jour 1
```bash
git add app/controllers/DashboardController.php app/views/dashboard/client.php
git commit -m "feat(user): espace client (commandes, livraisons)"
```
### Jour 2
```bash
git add app/views/dashboard/tracking.php
git commit -m "feat(user): page suivi de livraison (timeline)"
```
### Jour 3
```bash
git add app/controllers/ProfileController.php app/views/dashboard/profile.php
git commit -m "feat(user): page profil (voir + modifier infos)"
```
### Jour 4  (bascule ADMIN)
```bash
git add app/controllers/UserController.php app/views/dashboard/users_crud.php crud/Cruduser.php
git commit -m "feat(admin): CRUD utilisateurs"
```
### Jour 5  (ADMIN)
```bash
git add app/models/Order.php
git commit -m "feat(admin): getAllOrders, setStatut, getGlobalStats"
git add app/views/dashboard/deliveries.php app/views/dashboard/admin.php
git commit -m "feat(admin): onglet livraisons + changement de statut"
git commit -m "docs: module espace client & livraisons"
```

---

## Jour 5 — Intégration finale (ensemble)

```bash
git checkout main
git merge feature/p1 feature/p2 feature/p3 feature/p4
git add public/index.php database.sql
git commit -m "chore: intégration routes + schéma BDD complet"
git tag v1.0
```

---

## Tableau : qui fait quoi, quand (USER 🟢 / ADMIN 🔵)

| Jour | Personne 1            | Personne 2           | Personne 3            | Personne 4            |
|------|-----------------------|----------------------|-----------------------|-----------------------|
| J1   | 🟢 Modèle User        | 🟢 Modèle Produit    | 🟢 Modèle Cart        | 🟢 Espace client      |
| J2   | 🟢 Connexion          | 🟢 Contrôleur shop   | 🟢 Favoris + panier   | 🟢 Suivi livraison    |
| J3   | 🟢 Inscription        | 🟢 Vue boutique      | 🟢 Commande/checkout  | 🟢 Profil             |
| J4   | 🔵 Compte admin       | 🔵 CRUD produits     | 🔵 Dashboard stats    | 🔵 CRUD users         |
| J5   | 🟢🔵 Logout + sécurité | 🔵 Catég. + 🟢 sync  | 🟢 Checkout + 🔵 dash | 🔵 Livraisons         |

Chaque personne fait **3 jours USER puis 2 jours ADMIN** (avec un jour
mixte le J5) — équilibre client/admin pour tous.

## Conseils

- Committez chaque jour pour montrer la régularité.
- Évitez les commits vides : préférez un vrai petit changement.
- Pull/merge réguliers pour éviter les gros conflits.
- Une Pull Request par personne en fin de module (J5), relue par
  un·e autre membre avant fusion dans `main`.
