<?php

declare(strict_types=1);

namespace App\Cms;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use PDO;

final class CmsController extends Controller
{
    public function showBlogPost(Request $request, array $params): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('SELECT * FROM blog_posts WHERE slug = :slug AND status = :status LIMIT 1');
        $statement->execute(['slug' => $params['slug'], 'status' => 'published']);
        $post = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            return new Response('Post not found', 404);
        }

        return $this->view('store/blog_post', ['post' => $post]);
    }

    public function faq(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $faqs = $db->pdo()->query('SELECT question, answer FROM faqs ORDER BY position ASC')->fetchAll(PDO::FETCH_ASSOC);
        return $this->view('store/faq', ['faqs' => $faqs]);
    }
}
