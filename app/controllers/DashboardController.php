<?php
class DashboardController {

    private function requireAdmin(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /SouqTN/public/login");
            exit;
        }
    }

    // GET /dashboard/admin
    public function admin(): void {
        $this->requireAdmin();
        $statsModel     = new Stats;
        $stats          = $statsModel->getSummary();
        $recentUsers    = $statsModel->getRecentUsers();
        $recentProducts = $statsModel->getRecentProducts();
        $chart          = $statsModel->getUsersChart();
        $activeTab      = 'dashboard';
        require __DIR__ . '/../views/dashboard/admin.php';
    }

    // GET /admin/users
    public function users(): void {
        $this->requireAdmin();
        $stats     = (new Stats)->getSummary();
        $users     = (new User)->getAll();
        $activeTab = 'users';
        require __DIR__ . '/../views/dashboard/admin.php';
    }
    // GET /admin/products
public function products(): void {
    $this->requireAdmin();
    $stats     = (new Stats)->getSummary();
    $products  = (new Produit)->getAllWithStats();
    $activeTab = 'products';
    require __DIR__ . '/../views/dashboard/admin.php';
}

// GET /admin/deliveries — gestion des livraisons
public function deliveries(): void {
    $this->requireAdmin();
    $stats        = (new Stats)->getSummary();
    $orderModel   = new Order;
    $allOrders    = $orderModel->getAllOrders();
    $deliveryStats= $orderModel->getGlobalStats();
    $activeTab    = 'deliveries';
    require __DIR__ . '/../views/dashboard/admin.php';
}

// POST /admin/deliveries/status  { id, statut }  (AJAX)
public function setDeliveryStatus(): void {
    $this->requireAdmin();
    header('Content-Type: application/json');

    $id     = (int)($_POST['id'] ?? 0);
    $statut = $_POST['statut'] ?? '';

    if (!$id || !in_array($statut, ['en_cours', 'livree'], true)) {
        echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
        return;
    }

    $ok = (new Order)->setStatut($id, $statut);
    echo json_encode($ok
        ? ['status' => 'success', 'statut' => $statut]
        : ['status' => 'error', 'message' => 'Échec de la mise à jour']);
}

    // GET /dashboard/client
    public function client(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /SouqTN/public/login");
            exit;
        }
        $uid          = (int)$_SESSION['user_id'];
        $orderModel   = new Order;
        $orders       = $orderModel->getByUser($uid);
        $orderStats   = $orderModel->getStats($uid);
        $enCours      = array_filter($orders, fn($o) => $o['statut'] === 'en_cours');
        $livrees      = array_filter($orders, fn($o) => $o['statut'] === 'livree');
        require __DIR__ . '/../views/dashboard/client.php';
    }

    // GET /tracking — suivi de livraison détaillé
    public function tracking(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /SouqTN/public/login");
            exit;
        }
        $uid        = (int)$_SESSION['user_id'];
        $orderModel = new Order;
        $orders     = $orderModel->getByUser($uid);

        // Attache le détail des articles à chaque commande
        foreach ($orders as &$o) {
            $o['items'] = $orderModel->getItems((int)$o['id'], $uid);
        }
        unset($o);

        require __DIR__ . '/../views/dashboard/tracking.php';
    }
}

