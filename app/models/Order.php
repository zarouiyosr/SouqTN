<?php
/**
 * Modèle de commande SouqTN.
 * Une commande est créée à partir du panier de l'utilisateur,
 * avec un statut de livraison ('en_cours' ou 'livree').
 */
class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Transforme le panier de l'utilisateur en commande.
     * Vide le panier ensuite. Retourne l'ID de la commande, ou 0 si panier vide.
     */
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
            SELECT
              COUNT(*)                                          AS total_cmd,
              SUM(statut = 'en_cours')                          AS en_cours,
              SUM(statut = 'livree')                            AS livrees,
              COALESCE(SUM(total), 0)                            AS montant_total
            FROM orders WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_cmd' => 0, 'en_cours' => 0, 'livrees' => 0, 'montant_total' => 0
        ];
    }

    // ════════════════════ CÔTÉ ADMIN ════════════════════

    /** Toutes les commandes de tous les clients (gestion admin). */
    public function getAllOrders(): array {
        return $this->db->query("
            SELECT o.id, o.total, o.statut, o.created_at,
                   u.username, u.email,
                   COUNT(oi.id)                AS nb_articles,
                   COALESCE(SUM(oi.qty), 0)    AS nb_unites
            FROM orders o
            JOIN users u        ON u.id = o.user_id
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Change le statut d'une commande (admin). */
    public function setStatut(int $orderId, string $statut): bool {
        if (!in_array($statut, ['en_cours', 'livree'], true)) {
            return false;
        }
        $stmt = $this->db->prepare(
            "UPDATE orders SET statut = ? WHERE id = ?"
        );
        return $stmt->execute([$statut, $orderId]);
    }

    /** Statistiques globales de toutes les commandes (admin). */
    public function getGlobalStats(): array {
        return $this->db->query("
            SELECT
              COUNT(*)                                AS total_cmd,
              SUM(statut = 'en_cours')                AS en_cours,
              SUM(statut = 'livree')                  AS livrees,
              COALESCE(SUM(total), 0)                 AS chiffre_affaires
            FROM orders
        ")->fetch(PDO::FETCH_ASSOC) ?: [
            'total_cmd' => 0, 'en_cours' => 0, 'livrees' => 0, 'chiffre_affaires' => 0
        ];
    }
}
