<?php

declare(strict_types=1);

namespace App\Shared;

use App\Infrastructure\Database\Connection;
use PDO;

final class AppSettings
{
    public function __construct(private Connection $db)
    {
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        $this->ensureTable();

        $stmt = $this->db->pdo()->query('SELECT setting_key, setting_value FROM app_settings');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settings = $this->defaults();
        foreach ($rows as $row) {
            $key = (string)($row['setting_key'] ?? '');
            if ($key === '') {
                continue;
            }
            $settings[$key] = (string)($row['setting_value'] ?? '');
        }

        return $settings;
    }

    /**
     * @param array<string, string> $values
     */
    public function saveMany(array $values): void
    {
        $this->ensureTable();

        $allowed = array_keys($this->defaults());
        $insert = $this->db->pdo()->prepare(
            'INSERT INTO app_settings (setting_key, setting_value)
             VALUES (:setting_key, :setting_value)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = CURRENT_TIMESTAMP'
        );

        foreach ($values as $key => $value) {
            if (!in_array($key, $allowed, true)) {
                continue;
            }
            $insert->execute([
                'setting_key' => $key,
                'setting_value' => $value,
            ]);
        }
    }

    private function ensureTable(): void
    {
        $this->db->pdo()->exec(
            'CREATE TABLE IF NOT EXISTS app_settings (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(120) NOT NULL UNIQUE,
                setting_value TEXT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB'
        );
    }

    /**
     * @return array<string, string>
     */
    private function defaults(): array
    {
        return [
            'site_name' => 'WATCHES',
            'support_email' => 'info@watches.com',
            'support_phone' => '+1 (234) 567-890',
            'logo_url' => '',
            'favicon_url' => '',
            'footer_tagline' => 'Authenticated luxury watches from around the world.',
            'footer_copyright' => 'Luxury Watch Commerce. All rights reserved.',
            'currency_code' => 'USD',
            'hero_badge_text' => '100% Certified Authentic Luxury Watches',
            'maintenance_mode' => '0',
        ];
    }
}
