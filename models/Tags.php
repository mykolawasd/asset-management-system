<?php

namespace Models;

use \Core\Database;
use Core\Core;
use PDO;

class Tags {

    public static function getTagsByAssetId(int $assetId): array {
        $query = "SELECT t.* FROM tags t
                  JOIN asset_tags at ON t.id = at.tag_id
                  WHERE at.asset_id = :assetId";
        $stmt = Core::$db->query($query, [':assetId' => $assetId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllTags(): array {
        $query = "SELECT * FROM tags";
        $stmt = Core::$db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTagByName(string $name): ?array {
        $query = "SELECT * FROM tags WHERE name = :name LIMIT 1";
        $stmt = Core::$db->query($query, [':name' => $name]);
        $tag = $stmt->fetch(PDO::FETCH_ASSOC);
        return $tag ? $tag : null;
    }

    public static function createTag(string $name): int {
        $query = "INSERT INTO tags (name) VALUES (:name)";
        Core::$db->query($query, [':name' => $name]);
        return Core::$db->getLastInsertId();
    }

    public static function deleteOrphanTags(): void {
        $query = "DELETE FROM tags WHERE id NOT IN (SELECT DISTINCT tag_id FROM asset_tags)";
        Core::$db->query($query);
    }
}