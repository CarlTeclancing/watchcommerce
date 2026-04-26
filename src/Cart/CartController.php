<?php

declare(strict_types=1);

namespace App\Cart;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;

final class CartController extends Controller
{
    public function show(Request $request): Response
    {
        $cart = $_SESSION['cart'] ?? [];
        return $this->view('store/cart', ['cart' => $cart]);
    }

    public function addItem(Request $request): Response
    {
        $this->verifyCsrf($request);
        $watchId = (int)$request->input('watch_id');
        $quantity = max(1, (int)$request->input('quantity', 1));

        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('SELECT id, title, list_price FROM watches WHERE id = :id AND status = :status LIMIT 1');
        $statement->execute(['id' => $watchId, 'status' => 'published']);
        $watch = $statement->fetch();

        if (!$watch) {
            return $this->json(['message' => 'Watch unavailable'], 404);
        }

        $existingQty = (int)($_SESSION['cart'][$watchId]['quantity'] ?? 0);

        $_SESSION['cart'][$watchId] = [
            'watch_id' => (int)$watch['id'],
            'title' => $watch['title'],
            'quantity' => $existingQty + $quantity,
            'unit_price' => (float)$watch['list_price'],
        ];

        return $this->redirect('/cart');
    }

    public function updateItem(Request $request): Response
    {
        $this->verifyCsrf($request);
        $watchId = (int)$request->input('watch_id');
        $quantity = max(1, (int)$request->input('quantity', 1));

        if (isset($_SESSION['cart'][$watchId])) {
            $_SESSION['cart'][$watchId]['quantity'] = $quantity;
        }

        return $this->redirect('/cart');
    }

    public function removeItem(Request $request): Response
    {
        $this->verifyCsrf($request);
        $watchId = (int)$request->input('watch_id');
        unset($_SESSION['cart'][$watchId]);
        return $this->redirect('/cart');
    }
}
