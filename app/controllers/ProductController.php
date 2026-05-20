<?php

class ProductController {

    private function requireAdmin(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Accès refusé']);
            exit;
        }
    }

    // POST /cart/add — public
    public function addToCart(): void {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']); return;
        }

        $productId = (int)($_POST['id'] ?? 0);
        if (!$productId) {
            echo json_encode(['success' => false, 'message' => 'Produit invalide']); return;
        }

        (new Cart)->add($_SESSION['user_id'], $productId);
        echo json_encode(['success' => true]);
    }

    // POST /admin/products/save — créer ou modifier
    public function save(): void {
        $this->requireAdmin();
        header('Content-Type: application/json');

        $id          = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $name        = trim($_POST['name']        ?? '');
        $description = trim($_POST['description'] ?? '');
        $category    = trim($_POST['category']    ?? '');
        $price       = (float)($_POST['price']    ?? 0);
        $origPrice   = !empty($_POST['orig_price']) ? (float)$_POST['orig_price'] : null;
        $stock       = (int)($_POST['stock']      ?? 0);
        $imageUrl    = trim($_POST['image_url']   ?? '');

        if (strlen($name) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Nom trop court']); return;
        }
        if ($price <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Prix invalide']); return;
        }
        if ($stock < 0) {
            echo json_encode(['status' => 'error', 'message' => 'Stock invalide']); return;
        }

        $productModel = new Produit;

        try {
            if ($id) {
                $productModel->update($id, $name, $description, $category, $price, $stock, $origPrice, $imageUrl);
                echo json_encode([
                    'status'  => 'success',
                    'message' => 'Produit mis à jour',
                ]);
            } else {
                $productModel->create($name, $description, $category, $price, $stock, $origPrice, $imageUrl);
                $newId = $productModel->getLastInsertedId();
                echo json_encode([
                    'status'  => 'success',
                    'message' => 'Produit créé',
                    'product' => [
                        'id'          => $newId,
                        'name'        => $name,
                        'description' => $description,
                        'category'    => $category,
                        'price'       => $price,
                        'orig_price'  => $origPrice,
                        'stock'       => $stock,
                        'image_url'   => $imageUrl,
                    ]
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // POST /admin/products/delete
    public function delete(): void {
        $this->requireAdmin();
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID invalide']); return;
        }

        try {
            (new Produit)->delete($id);
            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

