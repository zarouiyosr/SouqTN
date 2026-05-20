<?php
class Stats {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getSummary(): array {
        return [
            'users'        => $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'products'     => $this->db->query("SELECT COUNT(*) FROM produits")->fetchColumn(),
            'admins'       => $this->db->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn(),
            'clients'      => $this->db->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn(),
            'orders_today' => $this->db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at)=CURDATE()")->fetchColumn(),
            'new_users_7d' => $this->db->query("SELECT COUNT(*) FROM users WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetchColumn(),
        ];
    }

    public function getRecentUsers(int $limit = 5): array {
        return $this->db->query(
            "SELECT username, email, role FROM users ORDER BY id DESC LIMIT $limit"
        )->fetchAll();
    }

    public function getRecentProducts(int $limit = 5): array {
        return $this->db->query(
            "SELECT name, price FROM produits ORDER BY id DESC LIMIT $limit"
        )->fetchAll();
    }

    public function getUsersChart(): array {
        $rows = $this->db->query("
            SELECT DATE(created_at) AS d, COUNT(*) AS total
            FROM users GROUP BY d ORDER BY d DESC LIMIT 7
        ")->fetchAll();
        $rows = array_reverse($rows);
        return [
            'labels' => array_column($rows, 'd'),
            'data'   => array_column($rows, 'total'),
        ];
    }
}

