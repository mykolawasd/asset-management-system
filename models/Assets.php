<?php

namespace Models;

use Core\Core;
use \PDO;



class Assets {
    public ?int $id = null;
    public string $title;
    public ?string $thumbnail_url;
    public string $description;
    public int $user_id;
    public string $created_at;

    public function __construct(string $title, ?string $thumbnail_url, string $description, int $user_id) {
        $this->title = $title;
        $this->thumbnail_url = $thumbnail_url;
        $this->description = $description;
        $this->user_id = $user_id;
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function create(): int {
        $query = "INSERT INTO assets (title, thumbnail_url, description, created_at, user_id)
                  VALUES (:title, :thumbnail_url, :description, :created_at, :user_id)";
        Core::$db->query($query, [
            ':title' => $this->title,
            ':thumbnail_url' => $this->thumbnail_url,
            ':description' => $this->description,
            ':created_at' => $this->created_at,
            ':user_id' => $this->user_id,
        ]);
        $this->id = Core::$db->getLastInsertId();
        return $this->id;
    }

    public static function getPaginatedAssets(int $page, int $perPage): array {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM assets LIMIT :perPage OFFSET :offset";
        $stmt = Core::$db->query($query, [
            ':perPage' => $perPage,
            ':offset' => $offset
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);


    }

    public static function getCountAssets(): int {
        $query = "SELECT COUNT(*) FROM assets";
        $stmt = Core::$db->query($query);
        return $stmt->fetchColumn();
    }


    public static function getAssetById(int $id): ?array {
        $query = "SELECT * FROM assets WHERE id = :id";
        $stmt = Core::$db->query($query, [':id' => $id]);
        $asset = $stmt->fetch(PDO::FETCH_ASSOC);
        return $asset ? $asset : null;
    }

    public static function getSimilarAssets(int $assetId, array $tagIds, int $limit = 10): array {
        if (empty($tagIds)) {
            return [];
        }
        $placeholders = [];
        $params = [];

        foreach ($tagIds as $index => $tagId) {
            $key = ":tag{$index}";
            $placeholders[] = $key;
            $params[$key] = $tagId;
        }
        $placeholdersStr = implode(',', $placeholders);
        $params[':assetId'] = $assetId;
        $params[':limit'] = $limit;
        
        $sql = "SELECT a.*, COUNT(*) as common_tags
                FROM asset_tags at
                JOIN assets a ON a.id = at.asset_id
                WHERE at.tag_id IN ($placeholdersStr)
                  AND a.id != :assetId
                GROUP BY a.id
                ORDER BY common_tags DESC
                LIMIT :limit";
        
        $stmt = Core::$db->query($sql, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function searchAssets(string $title, array $tagIds, int $page, int $perPage): array {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereParts = [];

        if (!empty($title)) {
            $whereParts[] = "a.title LIKE :title";
            $params[':title'] = '%' . $title . '%';
        }

        if (!empty($tagIds)) {
            $tagPlaceholders = [];
            foreach ($tagIds as $index => $tagId) {
                $key = ":tag$index";
                $tagPlaceholders[] = $key;
                $params[$key] = $tagId;
            }
            $whereParts[] = "at.tag_id IN (" . implode(',', $tagPlaceholders) . ")";
            $params[':tagCount'] = count($tagIds);

            $sql = "SELECT a.*, COUNT(DISTINCT at.tag_id) as matched_tags
                    FROM assets a
                    JOIN asset_tags at ON a.id = at.asset_id";
            if (!empty($whereParts)) {
                $sql .= " WHERE " . implode(' AND ', $whereParts);
            }
            $sql .= " GROUP BY a.id
                      HAVING COUNT(DISTINCT at.tag_id) = :tagCount
                      LIMIT :limit OFFSET :offset";
        } else {
            $sql = "SELECT a.* FROM assets a";
            if (!empty($whereParts)) {
                $sql .= " WHERE " . implode(' AND ', $whereParts);
            }
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $params[':limit'] = $perPage;
        $params[':offset'] = $offset;

        $stmt = Core::$db->query($sql, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getCountSearchAssets(string $title, array $tagIds): int {
        $params = [];
        $whereParts = [];

        if (!empty($title)) {
            $whereParts[] = "a.title LIKE :title";
            $params[':title'] = '%' . $title . '%';
        }

        if (!empty($tagIds)) {
            $tagPlaceholders = [];
            foreach ($tagIds as $index => $tagId) {
                $key = ":tag$index";
                $tagPlaceholders[] = $key;
                $params[$key] = $tagId;
            }
            $whereParts[] = "at.tag_id IN (" . implode(',', $tagPlaceholders) . ")";
            $params[':tagCount'] = count($tagIds);
            $sql = "SELECT COUNT(*) FROM (
                        SELECT a.id
                        FROM assets a
                        JOIN asset_tags at ON a.id = at.asset_id
                        WHERE " . implode(' AND ', $whereParts) . "
                        GROUP BY a.id
                        HAVING COUNT(DISTINCT at.tag_id) = :tagCount
                    ) as t";
        } else {
            $sql = "SELECT COUNT(*) FROM assets a";
            if (!empty($whereParts)) {
                $sql .= " WHERE " . implode(' AND ', $whereParts);
            }
        }
        $stmt = Core::$db->query($sql, $params);
        return (int)$stmt->fetchColumn();
    }

    public static function attachTag(int $assetId, int $tagId): void {
        $query = "INSERT INTO asset_tags (asset_id, tag_id) VALUES (:asset_id, :tag_id)";
        Core::$db->query($query, [
            ':asset_id' => $assetId,
            ':tag_id' => $tagId,
        ]);
    }

    public static function updateAsset(int $assetId, string $title, ?string $thumbnail_url, string $description): void {
        $query = "UPDATE assets 
                  SET title = :title, thumbnail_url = :thumbnail_url, description = :description 
                  WHERE id = :id";
        Core::$db->query($query, [
            ':title' => $title,
            ':thumbnail_url' => $thumbnail_url,
            ':description' => $description,
            ':id' => $assetId,
        ]);
    }

    public static function clearTags(int $assetId): void {
        $query = "DELETE FROM asset_tags WHERE asset_id = :assetId";
        Core::$db->query($query, [':assetId' => $assetId]);
    }
}

