<?php

class Produit {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllWithStats(): array {
        return $this->db->query("
            SELECT p.id, p.name, p.description, p.category,
                   p.price, p.orig_price, p.image_url, p.stock,
                   p.rating, p.reviews, p.badge, p.region, p.artisan,
                   (p.orig_price - p.price)            AS remise,
                   COUNT(o.id)                          AS total_commandes
            FROM produits p
            LEFT JOIN order_items o ON p.id = o.product_id
            GROUP BY p.id
            ORDER BY p.id ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory(string $category): array {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.description, p.category,
                   p.price, p.orig_price, p.image_url, p.stock,
                   p.rating, p.reviews, p.badge, p.region, p.artisan
            FROM produits p
            WHERE p.category = ?
            ORDER BY p.id ASC
        ");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(string $name, string $description, string $category, float $price, int $stock, ?float $origPrice = null, string $imageUrl = ''): void {
        $stmt = $this->db->prepare(
            "INSERT INTO produits(name, description, category, price, orig_price, stock, image_url)
             VALUES(?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $description, $category, $price, $origPrice, $stock, $imageUrl]);
    }

    public function getLastInsertedId(): int {
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $name, string $description, string $category, float $price, int $stock, ?float $origPrice = null, string $imageUrl = ''): void {
        $stmt = $this->db->prepare(
            "UPDATE produits SET name=?, description=?, category=?, price=?, orig_price=?, stock=?, image_url=? WHERE id=?"
        );
        $stmt->execute([$name, $description, $category, $price, $origPrice, $stock, $imageUrl, $id]);
    }

    public function delete(int $id): void {
        
        $this->db->prepare("DELETE FROM order_items WHERE product_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM produits WHERE id = ?")->execute([$id]);
    }
}

