<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Infrastructure\Database\Connection;
use App\Shared\AppSettings;
use PDO;
use PDOException;

final class AdminController extends Controller
{
    public function dashboard(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $kpis = [
            'inventory_count' => (int)$db->pdo()->query("SELECT COUNT(*) FROM watches WHERE status = 'published'")->fetchColumn(),
            'pending_orders' => (int)$db->pdo()->query("SELECT COUNT(*) FROM orders WHERE status IN ('pending_payment', 'paid', 'processing')")->fetchColumn(),
            'pending_valuations' => (int)$db->pdo()->query("SELECT COUNT(*) FROM sell_requests WHERE status = 'submitted'")->fetchColumn(),
        ];

        return $this->view('admin/dashboard', ['kpis' => $kpis]);
    }

    public function inventory(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $items = $db->pdo()->query('SELECT id, slug, title, brand_slug, reference_number, condition_grade, year_of_production, status, list_price FROM watches ORDER BY created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        return $this->view('admin/inventory', ['items' => $items]);
    }

    public function saveInventory(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/watches/';
        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        // Helper: move a single uploaded file and return its public URL
        $moveFile = function (array $fileEntry) use ($uploadDir, $allowedMime): ?string {
            if ($fileEntry['error'] !== UPLOAD_ERR_OK || $fileEntry['size'] < 1) {
                return null;
            }
            $mime = mime_content_type($fileEntry['tmp_name']);
            if (!in_array($mime, $allowedMime, true)) {
                return null;
            }
            $ext = match($mime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };
            $name = bin2hex(random_bytes(12)) . '.' . $ext;
            if (!move_uploaded_file($fileEntry['tmp_name'], $uploadDir . $name)) {
                return null;
            }
            return '/watch-commerce/public/uploads/watches/' . $name;
        };

        // Hero image
        $heroUrl = '';
        if (!empty($_FILES['hero_image']['tmp_name'])) {
            $heroUrl = $moveFile($_FILES['hero_image']) ?? '';
        }

        // Gallery images (multiple)
        $galleryUrls = [];
        if (!empty($_FILES['gallery_images']['tmp_name'])) {
            $files = $_FILES['gallery_images'];
            // Normalise to array-of-files format
            $count = is_array($files['tmp_name']) ? count($files['tmp_name']) : 1;
            for ($i = 0; $i < $count; $i++) {
                $entry = [
                    'tmp_name' => is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'],
                    'error'    => is_array($files['error'])    ? $files['error'][$i]    : $files['error'],
                    'size'     => is_array($files['size'])     ? $files['size'][$i]     : $files['size'],
                ];
                $url = $moveFile($entry);
                if ($url !== null) {
                    $galleryUrls[] = $url;
                }
            }
        }

        // If no hero was uploaded but gallery has images, use the first gallery image
        if ($heroUrl === '' && !empty($galleryUrls)) {
            $heroUrl = $galleryUrls[0];
        }

        $status = (string)$request->input('status', 'draft');
        $allowedStatuses = ['draft', 'published', 'reserved', 'sold', 'archived'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'draft';
        }

        $condition = (string)$request->input('condition_grade', 'very_good');
        $allowedConditions = ['new', 'excellent', 'very_good', 'good', 'fair'];
        if (!in_array($condition, $allowedConditions, true)) {
            $condition = 'very_good';
        }

        $sql = 'INSERT INTO watches
                    (slug, brand_slug, title, reference_number, condition_grade, year_of_production,
                     list_price, status, hero_image_url, gallery_json)
                VALUES
                    (:slug, :brand_slug, :title, :reference_number, :condition_grade, :year_of_production,
                     :list_price, :status, :hero_image_url, :gallery_json)';
        $statement = $db->pdo()->prepare($sql);
        $statement->execute([
            'slug'               => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', (string)$request->input('title'))) . '-' . time(),
            'brand_slug'         => strtolower((string)$request->input('brand_slug', 'unknown')),
            'title'              => (string)$request->input('title'),
            'reference_number'   => (string)$request->input('reference_number', ''),
            'condition_grade'    => $condition,
            'year_of_production' => $request->input('year_of_production') ? (int)$request->input('year_of_production') : null,
            'list_price'         => (float)$request->input('list_price', 0),
            'status'             => $status,
            'hero_image_url'     => $heroUrl,
            'gallery_json'       => !empty($galleryUrls) ? json_encode($galleryUrls) : null,
        ]);

        return $this->redirect('/admin/inventory');
    }

    public function updateInventory(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $id = (int)$request->input('watch_id');
        if ($id <= 0) {
            return $this->redirect('/admin/inventory');
        }

        $status = (string)$request->input('status', 'draft');
        $allowedStatuses = ['draft', 'published', 'reserved', 'sold', 'archived'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'draft';
        }

        $condition = (string)$request->input('condition_grade', 'very_good');
        $allowedConditions = ['new', 'excellent', 'very_good', 'good', 'fair'];
        if (!in_array($condition, $allowedConditions, true)) {
            $condition = 'very_good';
        }

        $statement = $db->pdo()->prepare(
            'UPDATE watches
             SET title = :title,
                 brand_slug = :brand_slug,
                 reference_number = :reference_number,
                 condition_grade = :condition_grade,
                 year_of_production = :year_of_production,
                 list_price = :list_price,
                 status = :status
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'title' => (string)$request->input('title', ''),
            'brand_slug' => strtolower((string)$request->input('brand_slug', 'unknown')),
            'reference_number' => (string)$request->input('reference_number', ''),
            'condition_grade' => $condition,
            'year_of_production' => $request->input('year_of_production') ? (int)$request->input('year_of_production') : null,
            'list_price' => (float)$request->input('list_price', 0),
            'status' => $status,
        ]);

        return $this->redirect('/admin/inventory');
    }

    public function deleteInventory(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $id = (int)$request->input('watch_id');
        if ($id <= 0) {
            return $this->redirect('/admin/inventory');
        }

        try {
            $statement = $db->pdo()->prepare('DELETE FROM watches WHERE id = :id');
            $statement->execute(['id' => $id]);
        } catch (PDOException) {
            // If hard delete is blocked by FK constraints, archive it to preserve referential integrity.
            $statement = $db->pdo()->prepare("UPDATE watches SET status = 'archived' WHERE id = :id");
            $statement->execute(['id' => $id]);
        }

        return $this->redirect('/admin/inventory');
    }

    public function orders(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $this->ensureOrdersPhoneColumn($db);
        $orders = $db->pdo()->query('SELECT id, order_number, status, payment_status, shipping_email, shipping_phone, total_amount, created_at FROM orders ORDER BY created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        return $this->view('admin/orders', ['orders' => $orders]);
    }

    public function updateOrderStatus(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $status = (string)$request->input('status', 'pending_payment');
        $allowedStatuses = ['pending_payment', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'pending_payment';
        }

        $statement = $db->pdo()->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $statement->execute(['status' => $status, 'id' => (int)$request->input('order_id')]);
        return $this->redirect('/admin/orders');
    }

    public function sellPipeline(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $requests = $db->pdo()->query('SELECT * FROM sell_requests ORDER BY created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        return $this->view('admin/sell_pipeline', ['requests' => $requests]);
    }

    public function quoteSellRequest(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('UPDATE sell_requests SET offered_price = :offered_price, status = :status, quoted_at = NOW() WHERE id = :id');
        $statement->execute([
            'offered_price' => (float)$request->input('offered_price', 0),
            'status' => 'quoted',
            'id' => (int)$request->input('sell_request_id'),
        ]);

        return $this->redirect('/admin/sell-pipeline');
    }

    public function cms(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $pages = $db->pdo()->query('SELECT id, slug, title, body_html, status FROM pages ORDER BY updated_at DESC')->fetchAll(PDO::FETCH_ASSOC);

        $editPage = null;
        $editId = (int)$request->input('edit', 0);
        if ($editId > 0) {
            $statement = $db->pdo()->prepare('SELECT id, slug, title, body_html, status FROM pages WHERE id = :id LIMIT 1');
            $statement->execute(['id' => $editId]);
            $editPage = $statement->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        return $this->view('admin/cms', ['pages' => $pages, 'editPage' => $editPage]);
    }

    public function savePage(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $status = (string)$request->input('status', 'draft');
        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        $pageId = (int)$request->input('page_id', 0);
        if ($pageId > 0) {
            $statement = $db->pdo()->prepare(
                'UPDATE pages
                 SET slug = :slug, title = :title, body_html = :body_html, status = :status
                 WHERE id = :id'
            );
            $statement->execute([
                'id' => $pageId,
                'slug' => (string)$request->input('slug'),
                'title' => (string)$request->input('title'),
                'body_html' => (string)$request->input('body_html'),
                'status' => $status,
            ]);
        } else {
            $statement = $db->pdo()->prepare('INSERT INTO pages (slug, title, body_html, status) VALUES (:slug, :title, :body_html, :status)');
            $statement->execute([
                'slug' => (string)$request->input('slug'),
                'title' => (string)$request->input('title'),
                'body_html' => (string)$request->input('body_html'),
                'status' => $status,
            ]);
        }

        return $this->redirect('/admin/cms');
    }

    public function deletePage(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);

        $pageId = (int)$request->input('page_id', 0);
        if ($pageId > 0) {
            $statement = $db->pdo()->prepare('DELETE FROM pages WHERE id = :id');
            $statement->execute(['id' => $pageId]);
        }

        return $this->redirect('/admin/cms');
    }

    public function users(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $users = $db->pdo()->query('SELECT id, name, email, two_factor_enabled, created_at FROM users ORDER BY created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        return $this->view('admin/users', ['users' => $users]);
    }

    public function settings(Request $request): Response
    {
        /** @var AppSettings $settings */
        $settings = $this->container->get(AppSettings::class);
        return $this->view('admin/settings', ['settings' => $settings->all()]);
    }

    public function saveSettings(Request $request): Response
    {
        $this->verifyCsrf($request);

        /** @var AppSettings $settings */
        $settings = $this->container->get(AppSettings::class);
        $current = $settings->all();

        $values = [
            'site_name' => trim((string)$request->input('site_name', $current['site_name'] ?? 'WATCHES')),
            'support_email' => trim((string)$request->input('support_email', $current['support_email'] ?? '')),
            'support_phone' => trim((string)$request->input('support_phone', $current['support_phone'] ?? '')),
            'footer_tagline' => trim((string)$request->input('footer_tagline', $current['footer_tagline'] ?? '')),
            'footer_copyright' => trim((string)$request->input('footer_copyright', $current['footer_copyright'] ?? '')),
            'currency_code' => strtoupper(substr(trim((string)$request->input('currency_code', $current['currency_code'] ?? 'USD')), 0, 3)),
            'hero_badge_text' => trim((string)$request->input('hero_badge_text', $current['hero_badge_text'] ?? '')),
            'maintenance_mode' => $request->input('maintenance_mode') ? '1' : '0',
            'logo_url' => (string)($current['logo_url'] ?? ''),
            'favicon_url' => (string)($current['favicon_url'] ?? ''),
        ];

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/settings/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $logoUpload = $this->saveSettingsAsset($_FILES['logo_file'] ?? null, $uploadDir, ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml']);
        if ($logoUpload !== null) {
            $values['logo_url'] = $logoUpload;
        }

        $faviconUpload = $this->saveSettingsAsset($_FILES['favicon_file'] ?? null, $uploadDir, ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/svg+xml']);
        if ($faviconUpload !== null) {
            $values['favicon_url'] = $faviconUpload;
        }

        $settings->saveMany($values);

        return $this->redirect('/admin/settings');
    }

    public function assignRole(Request $request): Response
    {
        $this->verifyCsrf($request);
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $statement = $db->pdo()->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id) ON DUPLICATE KEY UPDATE role_id = VALUES(role_id)');
        $statement->execute(['user_id' => (int)$request->input('user_id'), 'role_id' => (int)$request->input('role_id')]);
        return $this->redirect('/admin/users');
    }

    public function exportReport(Request $request): Response
    {
        /** @var Connection $db */
        $db = $this->container->get(Connection::class);
        $rows = $db->pdo()->query('SELECT order_number, status, payment_status, total_amount, created_at FROM orders ORDER BY created_at DESC LIMIT 500')->fetchAll(PDO::FETCH_ASSOC);

        $csv = "order_number,status,payment_status,total_amount,created_at\n";
        foreach ($rows as $row) {
            $csv .= sprintf(
                "%s,%s,%s,%.2f,%s\n",
                $row['order_number'],
                $row['status'],
                $row['payment_status'],
                (float)$row['total_amount'],
                $row['created_at']
            );
        }

        return new Response($csv, 200, ['Content-Type' => 'text/csv']);
    }

    /**
     * @param array<string, mixed>|null $file
     * @param string[] $allowedMime
     */
    private function saveSettingsAsset(?array $file, string $uploadDir, array $allowedMime): ?string
    {
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmp = (string)($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return null;
        }

        $mime = (string)mime_content_type($tmp);
        if (!in_array($mime, $allowedMime, true)) {
            return null;
        }

        $ext = match ($mime) {
            'image/png' => 'png',
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/x-icon', 'image/vnd.microsoft.icon' => 'ico',
            default => 'bin',
        };

        $name = bin2hex(random_bytes(12)) . '.' . $ext;
        if (!move_uploaded_file($tmp, $uploadDir . $name)) {
            return null;
        }

        return '/watch-commerce/public/uploads/settings/' . $name;
    }

    private function ensureOrdersPhoneColumn(Connection $db): void
    {
        $hasPhoneCol = (bool)$db->pdo()->query("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'shipping_phone'")->fetchColumn();
        if (!$hasPhoneCol) {
            $db->pdo()->exec("ALTER TABLE orders ADD COLUMN shipping_phone VARCHAR(40) NULL AFTER shipping_email");
        }
    }
}
