<?php

class Favori {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /** IDs des produits favoris de l'utilisateur. */
    public function getProductIdsByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT product_id FROM favoris WHERE user_id = ? ORDER BY date_ajout DESC"
        );
        $stmt->execute([$userId]);
        return array_column($stmt->fetchAll(), 'product_id');
    }

    /** Favoris avec le détail produit (pour affichage dans l'espace client). */
    public function getDetailedByUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.category, p.price, p.orig_price,
                   p.stock, p.region, p.artisan
            FROM favoris f
            JOIN produits p ON p.id = f.product_id
            WHERE f.user_id = ?
            ORDER BY f.date_ajout DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Vrai si le produit est déjà en favori. */
    public function exists(int $userId, int $productId): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM favoris WHERE user_id = ? AND product_id = ?"
        );
        $stmt->execute([$userId, $productId]);
        return $stmt->rowCount() > 0;
    }

    /** Ajoute un favori (ignore si déjà présent). */
    public function add(int $userId, int $productId): void {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO favoris(user_id, product_id) VALUES(?, ?)"
        );
        $stmt->execute([$userId, $productId]);
    }

    /** Retire un favori. */
    public function remove(int $userId, int $productId): void {
        $stmt = $this->db->prepare(
            "DELETE FROM favoris WHERE user_id = ? AND product_id = ?"
        );
        $stmt->execute([$userId, $productId]);
    }

    /**
     * Bascule l'état favori. Retourne le nouvel état :
     * true  = désormais en favori,
     * false = retiré des favoris.
     */
    public function toggle(int $userId, int $productId): bool {
        if ($this->exists($userId, $productId)) {
            $this->remove($userId, $productId);
            return false;
        }
        $this->add($userId, $productId);
        return true;
    }
}

