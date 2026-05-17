<?php
session_start();

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Router.php';

require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/Produit.php';
require_once __DIR__ . '/../app/models/Category.php';
require_once __DIR__ . '/../app/models/Cart.php';
require_once __DIR__ . '/../app/models/Favori.php';
require_once __DIR__ . '/../app/models/Stats.php';
require_once __DIR__ . '/../app/models/Order.php';

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ShopController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/CartController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';

$router = new Router('/SouqTN/public');

$router->get('/',           [ShopController::class,    'index']);
$router->post('/cart/add',  [ProductController::class, 'addToCart']);


$router->get('/cart/list',     [CartController::class, 'list']);
$router->post('/cart/add2',    [CartController::class, 'add']);
$router->post('/cart/setqty',  [CartController::class, 'setQty']);
$router->post('/cart/remove',  [CartController::class, 'remove']);
$router->post('/cart/clear',   [CartController::class, 'clear']);
$router->post('/cart/checkout',[CartController::class, 'checkout']);

$router->get('/wish/list',     [CartController::class, 'wishList']);
$router->post('/wish/toggle',  [CartController::class, 'wishToggle']);


$router->get('/login',      [AuthController::class, 'loginForm']);
$router->post('/login',     [AuthController::class, 'login']);
$router->get('/register',   [AuthController::class, 'registerForm']);
$router->post('/register',  [AuthController::class, 'register']);
$router->get('/logout',     [AuthController::class, 'logout']);


$router->get('/dashboard/admin',  [DashboardController::class, 'admin']);
$router->get('/dashboard/client', [DashboardController::class, 'client']);


$router->get('/tracking',        [DashboardController::class, 'tracking']);
$router->get('/profile',         [ProfileController::class, 'show']);
$router->post('/profile/update', [ProfileController::class, 'update']);


$router->get('/admin/users',    [DashboardController::class, 'users']);
$router->get('/admin/products', [DashboardController::class, 'products']);
$router->get('/admin/deliveries',[DashboardController::class, 'deliveries']);



$router->post('/admin/users/save',      [UserController::class,    'save']);
$router->post('/admin/users/delete',    [UserController::class,    'delete']);
$router->post('/admin/products/save',   [ProductController::class, 'save']);
$router->post('/admin/products/delete', [ProductController::class, 'delete']);
$router->post('/admin/deliveries/status', [DashboardController::class, 'setDeliveryStatus']);

$router->get('/products',         [ProductController::class, 'index']);
$router->get('/products/create',  [ProductController::class, 'createForm']);
$router->post('/products/store',  [ProductController::class, 'store']);
$router->post('/products/delete', [ProductController::class, 'delete']);

$router->dispatch();

