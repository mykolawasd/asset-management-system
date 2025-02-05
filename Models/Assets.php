<?php

namespace Models;

use Core\Core;
use \PDO;



class Assets {


    private string $title;
    private string $description;

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


    

}

