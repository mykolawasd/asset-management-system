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
}
