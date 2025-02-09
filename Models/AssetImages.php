<?php
namespace Models;

use Core\Core;
use PDO;

class AssetImages {
    public static function getImagesByAssetId(int $assetId): array {
        $query = "SELECT * FROM asset_images WHERE asset_id = :assetId ORDER BY sort_order ASC";
        $stmt = Core::$db->query($query, [
            ':assetId' => $assetId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createImage(int $assetId, string $url, int $sortOrder = 0): int {
        $query = "INSERT INTO asset_images (asset_id, url, sort_order, created_at)
                  VALUES (:asset_id, :url, :sort_order, NOW())";
        Core::$db->query($query, [
            ':asset_id' => $assetId,
            ':url' => $url,
            ':sort_order' => $sortOrder
        ]);
        return Core::$db->getLastInsertId();
    }
}
