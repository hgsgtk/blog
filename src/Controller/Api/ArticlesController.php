<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Model\Table\ArticlesTable;

/**
 * Class ArticlesController
 * @package App\Controller\Api
 * @property ArticlesTable Articles
 */
class ArticlesController extends AppController
{
    /**
     * @throws \Exception
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
    }

    /**
     * 記事一覧
     *
     * @return void
     */
    public function index()
    {
        // TODO: 記事一覧をデータベースから取得する
        $articles = $this->Articles->find('all');
        $this->set([
            '_serialize' => ['articles'],
            'articles' => $articles,
        ]);
    }
}
