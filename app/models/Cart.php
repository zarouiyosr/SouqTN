<?php

class Cart {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /** IDs des produits présents dans le panier de l'utilisateur. */
    public function getProductIdsByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT product_id FROM cart WHERE user_id = ?"
        );
        $stmt->execute([$userId]);
        return array_column($stmt->fetchAll(), 'product_id');
    }

    /** Lignes du panier avec quantité, pour affichage. */
    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT product_id, qty FROM cart WHERE user_id = ? ORDER BY date_ajout ASC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Ajoute un produit (ou +qty si déjà présent). */
    public function add(int $userId, int $productId, int $qty = 1): void {
        $stmt = $this->db->prepare(
            "INSERT INTO cart(user_id, product_id, qty) VALUES(?, ?, ?)
             ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)"
        );
        $stmt->execute([$userId, $productId, $qty]);
    }

    /** Modifie la quantité ; supprime la ligne si qty <= 0. */
    public function setQty(int $userId, int $productId, int $qty): void {
        if ($qty <= 0) {
            $this->remove($userId, $productId);
            return;
        }
        $stmt = $this->db->prepare(
            "UPDATE cart SET qty = ? WHERE user_id = ? AND product_id = ?"
        );
        $stmt->execute([$qty, $userId, $productId]);
    }

    /** Retire un produit du panier. */
    public function remove(int $userId, int $productId): void {
        $stmt = $this->db->prepare(
            "DELETE FROM cart WHERE user_id = ? AND product_id = ?"
        );
        $stmt->execute([$userId, $productId]);
    }

    /** Vide tout le panier de l'utilisateur. */
    public function clear(int $userId): void {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
}

