<?php

declare(strict_types=1);

namespace App\Catalog;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use PDO;

final class CatalogController extends Controller
{
    public function home(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        
        // Get featured watches
        $featured = $db->pdo()->query("SELECT id, slug, title, reference_number, condition_grade, list_price, hero_image_url, brand_slug, year_of_production FROM watches WHERE status = 'published' ORDER BY created_at DESC LIMIT 12")->fetchAll(PDO::FETCH_ASSOC);
        
        // Get brands grouped by brand_slug
        $brandsStmt = $db->pdo()->query("
            SELECT brand_slug, COUNT(*) as count
            FROM watches
            WHERE status = 'published'
            GROUP BY brand_slug
            ORDER BY count DESC
            LIMIT 10
        ");
        $brandsData = $brandsStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format brands for display
        $brands = [];
        foreach ($brandsData as $brand) {
            $brands[] = [
                'brand_slug' => $brand['brand_slug'],
                'brand_name' => ucwords(str_replace('-', ' ', $brand['brand_slug'])),
                'count' => $brand['count']
            ];
        }
        
        return $this->view('store/home', ['featured' => $featured, 'brands' => $brands]);
    }

    public function index(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $where = ["status = 'published'"];
        $bindings = [];

        if ($brand = $request->input('brand')) {
            $where[] = 'brand_slug = :brand';
            $bindings['brand'] = $brand;
        }

        if ($search = $request->input('q')) {
            $where[] = '(title LIKE :q OR reference_number LIKE :q)';
            $bindings['q'] = '%' . $search . '%';
        }

        $sort = match ($request->input('sort')) {
            'price_asc' => 'list_price ASC',
            'price_desc' => 'list_price DESC',
            default => 'created_at DESC',
        };

        $sql = 'SELECT id, slug, title, reference_number, condition_grade, year_of_production, list_price, hero_image_url
                FROM watches WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . $sort . ' LIMIT 50';

        $statement = $db->pdo()->prepare($sql);
        $statement->execute($bindings);
        $watches = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $this->view('store/catalog', ['watches' => $watches]);
    }

    public function show(Request $request, array $params): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('SELECT * FROM watches WHERE slug = :slug LIMIT 1');
        $statement->execute(['slug' => $params['slug']]);
        $watch = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$watch) {
            return new Response('Watch not found', 404);
        }

        return $this->view('store/product', ['watch' => $watch]);
    }
}
