<?php

declare(strict_types=1);

namespace App\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Http\Validator;
use PDO;

final class ApiController extends Controller
{
    public function watches(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $items = $db->pdo()->query('SELECT id, slug, title, reference_number, list_price FROM watches WHERE status = "published" LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        return $this->json(['data' => $items]);
    }

    public function watch(Request $request, array $params): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('SELECT * FROM watches WHERE id = :id LIMIT 1');
        $statement->execute(['id' => (int)$params['id']]);
        $item = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            return $this->json(['message' => 'Not found'], 404);
        }

        return $this->json(['data' => $item]);
    }

    public function sellRequest(Request $request): Response
    {
        [$data, $errors] = Validator::validate($request->post, [
            'contact_name' => 'required|min:2',
            'contact_email' => 'required|email',
            'brand' => 'required|min:2',
            'model_name' => 'required|min:2',
            'reference_number' => 'required|min:3',
            'condition_grade' => 'required',
        ]);

        if ($errors !== []) {
            return $this->json(['errors' => $errors], 422);
        }

        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare(
            'INSERT INTO sell_requests (contact_name, contact_email, brand, model_name, reference_number, condition_grade, asking_price, status)
             VALUES (:contact_name, :contact_email, :brand, :model_name, :reference_number, :condition_grade, :asking_price, :status)'
        );
        $statement->execute([
            'contact_name' => $data['contact_name'],
            'contact_email' => $data['contact_email'],
            'brand' => $data['brand'],
            'model_name' => $data['model_name'],
            'reference_number' => $data['reference_number'],
            'condition_grade' => $data['condition_grade'],
            'asking_price' => (float)$request->input('asking_price', 0),
            'status' => 'submitted',
        ]);

        return $this->json(['message' => 'created'], 201);
    }
}
