<?php

namespace Controllers;

use Core\Controller;
use Core\Core;
use Models\Assets;
use Models\Tags;
use Models\AssetDownloads;
use Models\AssetImages;
use Models\User;

class AssetsController extends Controller {


    public function __construct() {
        parent::__construct();
    }

    public function viewAction() {
        if (!isset($_SESSION['user'])) {
            return $this->redirect('/users/login'); 
        }

        $assetId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$assetId) {
            return $this->redirect('/assets');
        }
  

        $asset = Assets::getAssetById($assetId);
        if (!$asset) {
            return $this->render('views/404.php', ['title' => 'Asset Not Found']);
        }
        

        $tags = Tags::getTagsByAssetId($asset['id']);
        $tagIds = array_map(function($tag) {
            return $tag['id'];
        }, $tags);
        $downloads = AssetDownloads::getDownloadsByAssetId($asset['id']);
        $images = AssetImages::getImagesByAssetId($asset['id']);

        $similarAssets = Assets::getSimilarAssets($asset['id'], $tagIds, 9);

        Core::getInstance()->app['title'] = $asset['title'];
        return $this->render(null, [
            'asset' => $asset,
            'tags' => $tags,
            'downloads' => $downloads,
            'images' => $images,
            'similarAssets' => $similarAssets,
        ]);
    }



    public function createAction() {
        if (!isset($_SESSION['user'])) {
            return $this->redirect('/users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $tagsInput = trim($_POST['tags'] ?? '');
            
            if (empty($title)) {
                $errors['title'] = 'Title is required';
            }
            
            // Thumbnail
            $thumbnailUrl = '';
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $uploadDirThumb = 'uploads/thumbnails/';
            if (!is_dir($uploadDirThumb)) {
                mkdir($uploadDirThumb, 0777, true);
            }
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbFile = $_FILES['thumbnail'];
                $ext = strtolower(pathinfo($thumbFile['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExtensions)) {
                    $errors['thumbnail'] = 'Invalid file type for thumbnail.';
                } else {
                    $thumbFilename = uniqid('thumb_') . '.' . $ext;
                    $thumbDestination = $uploadDirThumb . $thumbFilename;
                    resizeImage($thumbFile['tmp_name'], $thumbDestination, 1280, 720);
                    $thumbnailUrl = '/' . $thumbDestination;
                }
            } else {
                $errors['thumbnail'] = 'Thumbnail is required.';
            }

            if (count($errors) > 0) {
                return $this->render(null, ['errors' => $errors]);
            }
            
            $userId = $_SESSION['user']['id'] ?? 0;
            // Create Assets object and save to DB (thumbnail_url is saved)
            $asset = new \Models\Assets($title, $thumbnailUrl, $description, $userId);
            $assetId = $asset->create();


            // Tags processing

            if (!empty($tagsInput)) {
                $tagIds = array_unique(explode(',', $tagsInput));
                foreach ($tagIds as $tagId) {
                    $tagId = trim($tagId);
                    if (!empty($tagId) && is_numeric($tagId)) {
                        \Models\Assets::attachTag($assetId, (int)$tagId);
                    }
                }
            }
            
            // Additional images processing (up to 10)
            $uploadDirImages = 'uploads/asset_images/';
            if (!is_dir($uploadDirImages)) {
                mkdir($uploadDirImages, 0777, true);
            }

            if (isset($_FILES['images'])) {
                $images = $_FILES['images'];
                $numImages = count($images['name']);
                $sortOrder = 1;
                for ($i = 0; $i < $numImages && $i < 10; $i++) {
                    if ($images['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($images['name'][$i], PATHINFO_EXTENSION));
                        if (in_array($ext, $allowedExtensions)) {
                            $imageFilename = uniqid('img_') . '.' . $ext;
                            $imageDestination = $uploadDirImages . $imageFilename;
                            resizeImage($images['tmp_name'][$i], $imageDestination, 1080, 1080);
                            $imageUrl = '/' . $imageDestination;
                            \Models\AssetImages::createImage($assetId, $imageUrl, $sortOrder);
                            $sortOrder++;
                        }
                    }
                }
            }
            
            if (isset($_POST['download_url']) && is_array($_POST['download_url'])) {
                $downloadUrls = $_POST['download_url'];
                $downloadVersions = $_POST['download_version'] ?? [];
                foreach ($downloadUrls as $index => $downloadUrl) {
                    $downloadUrl = trim($downloadUrl);
                    if (!empty($downloadUrl)) {
                        $version = trim($downloadVersions[$index] ?? '');
                        \Models\AssetDownloads::createDownload($assetId, $downloadUrl, $version);
                    }
                }
            }
            
            return $this->redirect('/assets/view?id=' . $assetId);
        }

        Core::getInstance()->app['title'] = 'Create Asset';
        return $this->render('views/assets/create.php');
    }

    public function indexAction() {
        Core::getInstance()->app['title'] = 'Assets Index';


        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $perPage = 9;
        //var_dump($page);

        $assets = Assets::getPaginatedAssets($page, $perPage);
        $totalPages = ceil(Assets::getCountAssets() / $perPage);

        $allTags = [];
        foreach ($assets as $asset) {
            $allTags[$asset['id']] = Tags::getTagsByAssetId($asset['id']);
        }

        //var_dump($tags);
        return $this->render(null, [
            'title' => 'Assets Index',
            'assets' => $assets,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'allTags' => $allTags,
        ]);
    }

    public function searchAction() {
        if (!isset($_SESSION['user'])) {
            return $this->redirect('/users/login');
        }

        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        $tagsInput = isset($_GET['tags']) ? trim($_GET['tags']) : '';
        $selectedTagIds = !empty($tagsInput) ? explode(',', $tagsInput) : [];

        $selectedTagIds = array_filter(array_map('intval', $selectedTagIds));


        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $perPage = 9;

        $assets = Assets::searchAssets($title, $selectedTagIds, $page, $perPage);
        $totalCount = Assets::getCountSearchAssets($title, $selectedTagIds);
        $totalPages = ceil($totalCount / $perPage);


        $allTags = [];
        foreach ($assets as $asset) {
            $allTags[$asset['id']] = Tags::getTagsByAssetId($asset['id']);
        }


        Core::getInstance()->app['title'] = 'Search Assets';

        return $this->render('views/assets/search.php', [
            'assets'      => $assets,
            'allTags'     => $allTags,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => $totalPages,
            'searchTitle' => $title,
            'searchTags'  => $selectedTagIds
        ]);
    }

    public function editAction() {
        if (!isset($_SESSION['user'])) {
            return $this->redirect('/users/login');
        }

        $assetId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$assetId) {
            return $this->redirect('/assets');
        }

        $asset = Assets::getAssetById($assetId);
        if (!$asset) {
            return $this->render('views/404.php', ['title' => 'Asset Not Found']);
        }
        
        
        $tags = Tags::getTagsByAssetId($assetId);
        $images = AssetImages::getImagesByAssetId($assetId);
        $downloads = AssetDownloads::getDownloadsByAssetId($assetId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $tagsInput = trim($_POST['tags'] ?? '');

            if (empty($title)) {
                $errors['title'] = 'Title is required';
            }


            $thumbnailUrl = $asset['thumbnail_url'];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $uploadDirThumb = 'uploads/thumbnails/';
            if (!is_dir($uploadDirThumb)) {
                mkdir($uploadDirThumb, 0777, true);
            }
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $thumbFile = $_FILES['thumbnail'];
                $ext = strtolower(pathinfo($thumbFile['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExtensions)) {
                    $errors['thumbnail'] = 'Invalid file type for thumbnail.';
                } else {
                    $thumbFilename = uniqid('thumb_') . '.' . $ext;
                    $thumbDestination = $uploadDirThumb . $thumbFilename;
                    resizeImage($thumbFile['tmp_name'], $thumbDestination, 1280, 720);
                    $thumbnailUrl = '/' . $thumbDestination;
                }
            }

            $deleteImageIds = $_POST['delete_images'] ?? [];
            if (!is_array($deleteImageIds)) {
                $deleteImageIds = [];
            }
            foreach ($deleteImageIds as $imageId) {
                AssetImages::deleteImage((int)$imageId);
            }

            $uploadDirImages = 'uploads/asset_images/';
            if (!is_dir($uploadDirImages)) {
                mkdir($uploadDirImages, 0777, true);
            }
            if (isset($_FILES['images'])) {
                $imagesFiles = $_FILES['images'];
                $numFiles = count($imagesFiles['name']);
                $sortOrder = count($images) + 1;
                for ($i = 0; $i < $numFiles && $i < 10; $i++) {
                    if ($imagesFiles['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($imagesFiles['name'][$i], PATHINFO_EXTENSION));
                        if (in_array($ext, $allowedExtensions)) {
                            $imageFilename = uniqid('img_') . '.' . $ext;
                            $imageDestination = $uploadDirImages . $imageFilename;
                            resizeImage($imagesFiles['tmp_name'][$i], $imageDestination, 1080, 1080);
                            $imageUrl = '/' . $imageDestination;
                            AssetImages::createImage($assetId, $imageUrl, $sortOrder);
                            $sortOrder++;
                        }
                    }
                }
            }

            \Models\AssetDownloads::deleteDownloadsByAssetId($assetId);

            if (isset($_POST['download_url']) && is_array($_POST['download_url'])) {
                $downloadUrls = $_POST['download_url'];
                $downloadVersions = $_POST['download_version'] ?? [];
                foreach ($downloadUrls as $index => $downloadUrl) {
                    $downloadUrl = trim($downloadUrl);
                    if (!empty($downloadUrl)) {
                        $version = trim($downloadVersions[$index] ?? '');
                        \Models\AssetDownloads::createDownload($assetId, $downloadUrl, $version);
                    }
                }
            }

            if (count($errors) === 0) {
                Assets::updateAsset($assetId, $title, $thumbnailUrl, $description);
                
                Assets::clearTags($assetId);
                if (!empty($tagsInput)) {
                    $tags = array_unique(explode(',', $tagsInput));
                    foreach ($tags as $tagValue) {
                        $tagValue = trim($tagValue);
                        if (!empty($tagValue)) {
                            if (is_numeric($tagValue)) {
                                // If the entered value is a number, it is an existing ID.
                                \Models\Assets::attachTag($assetId, (int)$tagValue);
                            } else {
                                // If the value is not a number, it is a new tag name.
                                $tag = \Models\Tags::getTagByName($tagValue);
                                if ($tag) {
                                    // If the tag with this name already exists, attach it.
                                    \Models\Assets::attachTag($assetId, (int)$tag['id']);
                                } else {
                                    // If the tag with this name does not exist, create it and attach it.
                                    $newTagId = \Models\Tags::createTag($tagValue);
                                    \Models\Assets::attachTag($assetId, $newTagId);
                                }
                            }
                        }
                    }
                }

                return $this->redirect('/assets/view?id=' . $assetId);
            }
            return $this->render(null, ['errors' => $errors, 'asset' => $asset, 'tags' => $tags, 'images' => $images]);
        }

        return $this->render('views/assets/edit.php', [
            'asset' => $asset,
            'tags' => $tags,
            'images' => $images,
            'downloads' => $downloads,
        ]);
    }

    public function deleteAction() {
        if (!User::isAdmin()) {
            return $this->redirect('/assets');
        }

        $assetId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$assetId) {
            return $this->redirect('/assets');
        }

        $asset = Assets::getAssetById($assetId);
        if (!$asset) {
            return $this->render('views/404.php', ['title' => 'Asset Not Found']);
        }
                
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $images = AssetImages::getImagesByAssetId($assetId);
            foreach ($images as $image) {
                AssetImages::deleteImage($image['id']);
            }
            Core::$db->query("DELETE FROM asset_tags WHERE asset_id = :assetId", [':assetId' => $assetId]);
            Core::$db->query("DELETE FROM asset_downloads WHERE asset_id = :assetId", [':assetId' => $assetId]);
            Core::$db->query("DELETE FROM assets WHERE id = :id", [':id' => $assetId]);
            if (!empty($asset['thumbnail_url'])) {
                $thumbPath = ltrim($asset['thumbnail_url'], '/');
                if (file_exists($thumbPath)) {
                    unlink($thumbPath);
                }
            }

            Tags::deleteOrphanTags();
            AssetDownloads::cleanupOrphanDownloads();
            return $this->redirect('/assets');
        }

        

        return $this->render('views/assets/delete.php', ['asset' => $asset]);
    }
}


