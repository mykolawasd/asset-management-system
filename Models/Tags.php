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

}