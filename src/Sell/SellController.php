<?php

declare(strict_types=1);

namespace App\Sell;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use App\Infrastructure\Http\Validator;
use App\Infrastructure\Security\Auth;
use PDO;

final class SellController extends Controller
{
    public function showForm(Request $request): Response
    {
        return $this->view('sell/form');
    }

    public function createRequest(Request $request): Response
    {
        $this->verifyCsrf($request);
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

        return $this->json(['message' => 'Sell request submitted'], 201);
    }

    public function acceptQuote(Request $request, array $params): Response
    {
        $this->verifyCsrf($request);
        /** @var Auth $auth */
        $auth = $this->container->get(Auth::class);
        if (!$auth->check()) {
            return $this->redirect('/login');
        }

        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare(
            'UPDATE sell_requests SET status = :status, quote_accepted_at = NOW() WHERE id = :id AND status = :current_status'
        );
        $statement->execute([
            'status' => 'quote_accepted',
            'id' => (int)$params['id'],
            'current_status' => 'quoted',
        ]);

        return $this->json(['message' => 'Quote accepted']);
    }
}
