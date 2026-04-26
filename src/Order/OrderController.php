<?php

declare(strict_types=1);

namespace App\Order;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Http\Validator;
use App\Infrastructure\Security\Auth;
use PDO;
use Throwable;

final class OrderController extends Controller
{
    public function showCheckout(Request $request): Response
    {
        return $this->view('store/checkout', ['cart' => $_SESSION['cart'] ?? []]);
    }

    public function placeOrder(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);
        if (!$auth->check()) {
            return $this->redirect('/login');
        }

        $cart = $_SESSION['cart'] ?? [];
        if ($cart === []) {
            return $this->json(['message' => 'Cart is empty'], 422);
        }

        [$data, $errors] = Validator::validate($request->post, [
            'shipping_name' => 'required|min:2',
            'shipping_email' => 'required|email',
            'shipping_phone' => 'required|min:7',
            'shipping_address' => 'required|min:8',
        ]);

        if ($errors !== []) {
            return $this->json(['errors' => $errors], 422);
        }

        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $pdo = $db->pdo();

        $hasPhoneCol = (bool)$pdo->query("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_phone'")->fetchColumn();
        if (!$hasPhoneCol) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN shipping_phone VARCHAR(40) NULL AFTER shipping_email");
        }

        $total = array_reduce($cart, static fn ($carry, $item) => $carry + ($item['quantity'] * $item['unit_price']), 0.0);

        try {
            $pdo->beginTransaction();

            $orderStatement = $pdo->prepare(
                 'INSERT INTO orders (user_id, order_number, status, payment_status, shipping_name, shipping_email, shipping_phone, shipping_address, subtotal_amount, total_amount)
                  VALUES (:user_id, :order_number, :status, :payment_status, :shipping_name, :shipping_email, :shipping_phone, :shipping_address, :subtotal_amount, :total_amount)'
            );

            $orderNumber = 'ORD-' . strtoupper(bin2hex(random_bytes(4)));
            $orderStatement->execute([
                'user_id' => $auth->userId(),
                'order_number' => $orderNumber,
                'status' => 'pending_payment',
                'payment_status' => 'unpaid',
                'shipping_name' => $data['shipping_name'],
                'shipping_email' => $data['shipping_email'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_address' => $data['shipping_address'],
                'subtotal_amount' => $total,
                'total_amount' => $total,
            ]);

            $orderId = (int)$pdo->lastInsertId();
            $itemStatement = $pdo->prepare(
                'INSERT INTO order_items (order_id, watch_id, title_snapshot, unit_price, quantity, line_total) VALUES (:order_id, :watch_id, :title, :unit_price, :quantity, :line_total)'
            );

            $inventoryStatement = $pdo->prepare('UPDATE watches SET status = :status WHERE id = :id AND status = :current_status');

            foreach ($cart as $item) {
                $itemStatement->execute([
                    'order_id' => $orderId,
                    'watch_id' => $item['watch_id'],
                    'title' => $item['title'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                ]);

                $inventoryStatement->execute([
                    'status' => 'reserved',
                    'id' => $item['watch_id'],
                    'current_status' => 'published',
                ]);

                if ($inventoryStatement->rowCount() === 0) {
                    throw new \RuntimeException('Inventory race detected for watch id ' . $item['watch_id']);
                }
            }

            $pdo->commit();
            unset($_SESSION['cart']);

            return $this->redirect('/orders/' . $orderId);
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            return $this->json(['message' => 'Unable to place order', 'error' => $exception->getMessage()], 500);
        }
    }

    public function show(Request $request, array $params): Response
    {
        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);
        if (!$auth->check()) {
            return $this->redirect('/login');
        }

        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('SELECT * FROM orders WHERE id = :id AND user_id = :user_id');
        $statement->execute(['id' => (int)$params['id'], 'user_id' => $auth->userId()]);
        $order = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return new Response('Order not found', 404);
        }

        return $this->view('store/order_success', ['order' => $order]);
    }
}
