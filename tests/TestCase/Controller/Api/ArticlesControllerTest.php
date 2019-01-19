<?php
namespace App\Test\TestCase\Controller\Api;

use App\Controller\Api\ArticlesController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Api\ArticlesController Test Case
 */
class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Articles'
    ];

    /**
     * @test
     *
     * @throws \PHPUnit\Exception
     *
     * @return void
     */
    public function 記事一覧取得にて成功レスポンスが返却される()
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json'
            ],
        ]);
        $this->get('/api/articles/index');

        $this->assertResponseOk();
        $this->assertSame('application/json', $this->_response->getType());
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \PHPUnit\Exception
     */
    public function 記事一覧取得にて一覧情報が返却される()
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $this->get('/api/articles/index');

        $expected = [
            'articles' => [
                [
                    'id' => 1,
                    'user_id' => 1,
                    'title' => 'First Article',
                    'slug' => 'first',
                    'body' => 'First Article Body',
                    'published' => 1,
                    'created' => '2018-01-07T15:47:01+00:00',
                    'modified' => '2018-01-07T15:47:02+00:00',
                ],
            ],
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertSame($expected, (string)$this->_response->getBody());
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \PHPUnit\Exception
     */
    public function 記事一覧取得にてレコードがない場合、0件の情報が返却される()
    {
        $Articles = TableRegistry::getTableLocator()->get('Articles');
        $Articles->deleteAll([]);

        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $this->get('/api/articles/index');

        $expected = [
            "articles" => [],
        ];
        $expected = json_encode($expected, JSON_PRETTY_PRINT);
        $this->assertSame($expected, (string)$this->_response->getBody());
    }
}