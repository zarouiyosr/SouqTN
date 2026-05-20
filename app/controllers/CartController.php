<?php

class CartController {

    private function requireLogin(): bool {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Non connecté', 'auth' => false]);
            return false;
        }
        return true;
    }

    private function json($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // ───────────────────────── PANIER ─────────────────────────

    // GET /cart/list
    public function list(): void {
        if (!$this->requireLogin()) return;
        $items = (new Cart)->getByUser($_SESSION['user_id']);
        $this->json(['success' => true, 'cart' => $items]);
    }

    // POST /cart/add   { id, qty? }
    public function add(): void {
        if (!$this->requireLogin()) return;
        $productId = (int)($_POST['id'] ?? 0);
        $qty       = max(1, (int)($_POST['qty'] ?? 1));
        if (!$productId) { $this->json(['success' => false, 'message' => 'Produit invalide']); return; }

        (new Cart)->add($_SESSION['user_id'], $productId, $qty);
        $this->json(['success' => true]);
    }

    // POST /cart/setqty   { id, qty }
    public function setQty(): void {
        if (!$this->requireLogin()) return;
        $productId = (int)($_POST['id']  ?? 0);
        $qty       = (int)($_POST['qty'] ?? 0);
        if (!$productId) { $this->json(['success' => false, 'message' => 'Produit invalide']); return; }

        (new Cart)->setQty($_SESSION['user_id'], $productId, $qty);
        $this->json(['success' => true]);
    }

    // POST /cart/remove   { id }
    public function remove(): void {
        if (!$this->requireLogin()) return;
        $productId = (int)($_POST['id'] ?? 0);
        if (!$productId) { $this->json(['success' => false, 'message' => 'Produit invalide']); return; }

        (new Cart)->remove($_SESSION['user_id'], $productId);
        $this->json(['success' => true]);
    }

    // POST /cart/clear
    public function clear(): void {
        if (!$this->requireLogin()) return;
        (new Cart)->clear($_SESSION['user_id']);
        $this->json(['success' => true]);
    }

    // ───────────────────────── FAVORIS ────────────────────────

    // GET /wish/list
    public function wishList(): void {
        if (!$this->requireLogin()) return;
        $ids = (new Favori)->getProductIdsByUser($_SESSION['user_id']);
        $this->json(['success' => true, 'wish' => array_map('intval', $ids)]);
    }

    // POST /wish/toggle   { id }
    public function wishToggle(): void {
        if (!$this->requireLogin()) return;
        $productId = (int)($_POST['id'] ?? 0);
        if (!$productId) { $this->json(['success' => false, 'message' => 'Produit invalide']); return; }

        $nowFav = (new Favori)->toggle($_SESSION['user_id'], $productId);
        $this->json(['success' => true, 'favori' => $nowFav]);
    }

    // ──────────────────────── CHECKOUT ────────────────────────

    // POST /cart/checkout — transforme le panier en commande
    public function checkout(): void {
        if (!$this->requireLogin()) return;
        try {
            $orderId = (new Order)->createFromCart($_SESSION['user_id']);
            if ($orderId === 0) {
                $this->json(['success' => false, 'message' => 'Votre panier est vide']);
                return;
            }
            $this->json(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Erreur lors de la commande']);
        }
    }
}

