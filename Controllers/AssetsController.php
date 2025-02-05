<?php

namespace Controllers;

use Core\Controller;
use Core\Core;
use Models\Assets;
use Models\Tags;

class AssetsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function viewAction() {
        Core::getInstance()->app['title'] = 'Assets View';
        return $this->render(null, [
            'title' => 'Assets View',
            'content' => 'Assets View'
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


        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
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


