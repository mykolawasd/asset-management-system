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
    
    public static function createDownload(int $assetId, string $url, ?string $version = null): int {
        $query = "INSERT INTO asset_downloads (asset_id, url, version, created_at)
                  VALUES (:asset_id, :url, :version, NOW())";
        Core::$db->query($query, [
            ':asset_id' => $assetId,
            ':url' => $url,
            ':version' => $version
        ]);
        return Core::$db->getLastInsertId();
    }

    public static function deleteDownload(int $downloadId): void {
        $query = "DELETE FROM asset_downloads WHERE id = :id";
        Core::$db->query($query, [':id' => $downloadId]);
    }

    public static function cleanupOrphanDownloads(): void {
        $query = "DELETE ad FROM asset_downloads ad
                  LEFT JOIN assets a ON ad.asset_id = a.id
                  WHERE a.id IS NULL";
        Core::$db->query($query);
    }

    public static function deleteDownloadsByAssetId(int $assetId): void {
        $query = "DELETE FROM asset_downloads WHERE asset_id = :assetId";
        Core::$db->query($query, [':assetId' => $assetId]);
    }
}
