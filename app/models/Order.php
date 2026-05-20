<?php

class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    
    public function createFromCart(int $userId): int {
        // Récupère les lignes du panier avec le prix actuel des produits
        $stmt = $this->db->prepare("
            SELECT c.product_id, c.qty, p.price
            FROM cart c
            JOIN produits p ON p.id = c.product_id
            WHERE c.user_id = ?
        ");
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$items) return 0;

        $total = 0;
        foreach ($items as $it) {
            $total += $it['price'] * $it['qty'];
        }

        $this->db->beginTransaction();
        try {
            $ins = $this->db->prepare(
                "INSERT INTO orders(user_id, total, statut) VALUES(?, ?, 'en_cours')"
            );
            $ins->execute([$userId, $total]);
            $orderId = (int)$this->db->lastInsertId();

            $line = $this->db->prepare(
                "INSERT INTO order_items(order_id, product_id, qty, price) VALUES(?, ?, ?, ?)"
            );
            foreach ($items as $it) {
                $line->execute([$orderId, $it['product_id'], $it['qty'], $it['price']]);
            }

            // Vide le panier
            $this->db->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$userId]);

            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Toutes les commandes d'un utilisateur, plus récentes d'abord. */
    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT o.id, o.total, o.statut, o.created_at,
                   COUNT(oi.id) AS nb_articles,
                   COALESCE(SUM(oi.qty), 0) AS nb_unites
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            WHERE o.user_id = ?
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Détail des articles d'une commande (sécurisé par user). */
    public function getItems(int $orderId, int $userId): array {
        $stmt = $this->db->prepare("
            SELECT oi.qty, oi.price, p.name, p.category
            FROM order_items oi
            JOIN orders o   ON o.id = oi.order_id
            JOIN produits p ON p.id = oi.product_id
            WHERE oi.order_id = ? AND o.user_id = ?
        ");
        $stmt->execute([$orderId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Statistiques rapides pour le tableau de bord client. */
    public function getStats(int $userId): array {
        $stmt = $this->db->prepare("

