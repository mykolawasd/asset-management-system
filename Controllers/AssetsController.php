<?php

namespace Controllers;

use Core\Controller;
use Core\Core;
use Models\Assets;
use Models\Tags;
use Models\AssetDownloads;
use Models\AssetImages;

class AssetsController extends Controller {


    public function __construct() {
        parent::__construct();
    }

    public function viewAction() {
        $assetId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$assetId) {
            return $this->redirect('/Assets');
        }
  

        $asset = Assets::getAssetById($assetId);
        if (!$asset) {
            return $this->render('Views/404.php', ['title' => 'Asset Not Found']);
        }
        

        $tags = Tags::getTagsByAssetId($asset['id']);
        $downloads = AssetDownloads::getDownloadsByAssetId($asset['id']);
        $images = AssetImages::getImagesByAssetId($asset['id']);


        Core::getInstance()->app['title'] = $asset['title'];
        return $this->render(null, [
            'asset' => $asset,
            'tags' => $tags,
            'downloads' => $downloads,
            'images' => $images,
        ]);
    }



    public function createAction() {
        Core::getInstance()->app['title'] = 'Assets Create';
        return $this->render(null, [
            'title' => 'Assets Create',
            'content' => 'Assets Create'
        ]);
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



    

}


