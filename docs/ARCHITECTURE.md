# Architecture — SouqTN

Application **PHP MVC** (Model–View–Controller) pour une boutique
e-commerce d'artisanat tunisien.

## Schéma global

```
Navigateur
    │
    ▼
public/index.php  ──►  core/Router.php  ──►  Controller  ──►  Model  ──►  BDD MySQL
                                                  │
                                                  ▼
                                                View (HTML)
```

## Dossiers

| Dossier            | Rôle                                              |
|--------------------|---------------------------------------------------|
| `public/`          | Point d'entrée unique (`index.php`), CSS, uploads |
| `core/`            | `Database.php` (PDO), `Router.php` (routage)      |
| `app/models/`      | Accès aux données (une classe par entité)         |
| `app/controllers/` | Logique métier, reçoit les requêtes               |
| `app/views/`       | Pages HTML (boutique, auth, dashboard)            |
| `crud/`            | Scripts CRUD autonomes (admin)                    |
| `docs/`            | Documentation du projet                           |
| `.github/`         | Templates issues/PR, CI                           |

## Modèles (entités)

- **User** — utilisateurs (admin / client)
- **Produit** — catalogue produits
- **Category** — catégories
- **Cart** — panier (persistant)
- **Favori** — favoris (persistant)
- **Order** — commandes + livraisons
- **Stats** — statistiques dashboard

## Flux principaux

### Authentification
`login` → `AuthController::login()` → `User::findByEmail()` →
vérif mot de passe → session → redirection (admin/client).

### Commande
Panier (`Cart`) → bouton « Passer la commande » →
`CartController::checkout()` → `Order::createFromCart()` →
table `orders` + `order_items` → panier vidé.

### Livraison
Admin → onglet Livraisons → `Order::setStatut()` →
statut `en_cours` ↔ `livree` → visible dans le suivi client.

## Base de données

7 tables : `users`, `produits`, `categories`, `cart`, `favoris`,
`orders`, `order_items`. Voir `database.sql` pour le schéma complet.

## Sécurité

- Mots de passe hachés avec `password_hash()` (bcrypt)
- Requêtes préparées PDO (anti-injection SQL)
- Contrôle de rôle (`requireAdmin()`) sur les pages admin
- Échappement HTML (`htmlspecialchars`) dans les vues
