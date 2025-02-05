<?php
namespace Models;

use Core\Core;
use PDO;

class AssetDownloads {
    public static function getDownloadsByAssetId(int $assetId): array {
        $query = "SELECT * FROM asset_downloads WHERE asset_id = :assetId";
        $stmt = Core::$db->query($query, [
            ':assetId' => $assetId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
