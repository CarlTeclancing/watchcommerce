<?php

declare(strict_types=1);

use App\Admin\AdminController;
use App\Api\ApiController;
use App\Auth\AuthController;
use App\Cart\CartController;
use App\Catalog\CatalogController;
use App\Cms\CmsController;
use App\Core\Router;
use App\Order\OrderController;
use App\Sell\SellController;

return static function (Router $router): void {
    $router->get('/', [CatalogController::class, 'home']);
    $router->get('/watches', [CatalogController::class, 'index']);
    $router->get('/watches/{slug}', [CatalogController::class, 'show']);

    $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);
    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);
    $router->get('/2fa', [AuthController::class, 'showTwoFactor']);
    $router->post('/2fa', [AuthController::class, 'verifyTwoFactor']);
    $router->post('/logout', [AuthController::class, 'logout']);

    $router->get('/cart', [CartController::class, 'show']);
    $router->post('/cart/items', [CartController::class, 'addItem']);
    $router->post('/cart/items/update', [CartController::class, 'updateItem']);
    $router->post('/cart/items/remove', [CartController::class, 'removeItem']);

    $router->get('/checkout', [OrderController::class, 'showCheckout']);
    $router->post('/checkout', [OrderController::class, 'placeOrder']);
    $router->get('/orders/{id}', [OrderController::class, 'show']);

    $router->get('/sell', [SellController::class, 'showForm']);
    $router->post('/sell', [SellController::class, 'createRequest']);
    $router->post('/sell/{id}/accept', [SellController::class, 'acceptQuote']);

    $router->get('/blog/{slug}', [CmsController::class, 'showBlogPost']);
    $router->get('/faq', [CmsController::class, 'faq']);

    $router->get('/admin', [AdminController::class, 'dashboard'], ['auth', 'admin']);
    $router->get('/admin/inventory', [AdminController::class, 'inventory'], ['auth', 'admin']);
    $router->post('/admin/inventory', [AdminController::class, 'saveInventory'], ['auth', 'admin']);
    $router->post('/admin/inventory/update', [AdminController::class, 'updateInventory'], ['auth', 'admin']);
    $router->post('/admin/inventory/delete', [AdminController::class, 'deleteInventory'], ['auth', 'admin']);
    $router->get('/admin/orders', [AdminController::class, 'orders'], ['auth', 'admin']);
    $router->post('/admin/orders/status', [AdminController::class, 'updateOrderStatus'], ['auth', 'admin']);
    $router->get('/admin/sell-pipeline', [AdminController::class, 'sellPipeline'], ['auth', 'admin']);
    $router->post('/admin/sell-pipeline/quote', [AdminController::class, 'quoteSellRequest'], ['auth', 'admin']);
    $router->get('/admin/cms', [AdminController::class, 'cms'], ['auth', 'admin']);
    $router->post('/admin/cms/page', [AdminController::class, 'savePage'], ['auth', 'admin']);
    $router->post('/admin/cms/page/delete', [AdminController::class, 'deletePage'], ['auth', 'admin']);
    $router->get('/admin/users', [AdminController::class, 'users'], ['auth', 'admin']);
    $router->post('/admin/users/role', [AdminController::class, 'assignRole'], ['auth', 'admin']);
    $router->get('/admin/settings', [AdminController::class, 'settings'], ['auth', 'admin']);
    $router->post('/admin/settings', [AdminController::class, 'saveSettings'], ['auth', 'admin']);
    $router->get('/admin/reports/export', [AdminController::class, 'exportReport'], ['auth', 'admin']);

    $router->get('/api/v1/watches', [ApiController::class, 'watches']);
    $router->get('/api/v1/watches/{id}', [ApiController::class, 'watch']);
    $router->post('/api/v1/sell-requests', [ApiController::class, 'sellRequest']);
};
