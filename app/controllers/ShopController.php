<?php
class ShopController {

    public function index(): void {
        $products   = (new Produit)->getAllWithStats();
        $userCart   = [];

        if (isset($_SESSION['user_id'])) {
            $userCart = (new Cart)->getProductIdsByUser($_SESSION['user_id']);
        }

        $totalProducts = count($products);
        $totalStock    = array_sum(array_column($products, 'stock'));

        require __DIR__ . '/../views/shop/index.php';
    }
}


