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
        
        // Get pagination parameters from query string
        $page = (int)($request->query['page'] ?? 1);
        $perPage = (int)($request->query['per_page'] ?? 10);
        
        // Ensure valid pagination values
        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100)); // Max 100 per page
        
        $offset = ($page - 1) * $perPage;
        
        // Fetch paginated watches with all necessary fields
        $statement = $db->pdo()->prepare(
            'SELECT id, slug, title, brand_slug, reference_number, condition_grade, year_of_production, list_price, hero_image_url 
             FROM watches 
             WHERE status = "published" 
             ORDER BY created_at DESC 
             LIMIT :limit OFFSET :offset'
        );
        $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $items = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        return $this->json(['watches' => $items]);
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
