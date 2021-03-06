<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArticlesTable Test Case
 */
final class ArticlesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArticlesTable
     */
    protected $Articles;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Articles',
        'app.Users',
        'app.Tags',
        'app.ArticlesTags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Articles') ? [] : ['className' => ArticlesTable::class];
        $this->Articles = TableRegistry::getTableLocator()->get('Articles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Articles);

        parent::tearDown();
    }

    /**
     * @test
     *
     * @covers ::beforeSave
     */
    public function beforeSave_slugに値が設定される()
    {
        $article = $this->Articles->newEntity(
            [
                'user_id' => 1,
                'title' => str_repeat('a', 10),
                'body' => str_repeat('a', 10),
            ]
        );
        $result = $this->Articles->save($article);

        $this->assertInstanceOf('App\Model\Entity\Article', $result);
        $this->assertSame(str_repeat('a', 10), $result->slug);
    }

    /**
     * @test
     *
     * @dataProvider dataProvider_validationDefault
     *
     * @param array $data
     * @param string $expected
     */
    public function validationDefault(array $data, string $expected): void
    {
        $article = $this->Articles->newEntity($data);
        $this->assertSame([$expected], array_keys($article->getErrors()));
    }

    /**
     * @return array
     */
    public function dataProvider_validationDefault(): array
    {
        return [
            'titleが空' => [
                'data' => [
                    'title' => '',
                    'body' => str_repeat('a', 10),
                ],
                'expected' => 'title',
            ],
            'titleが9文字以下' => [
                'data' => [
                    'title' => str_repeat('a', 9),
                    'body' => str_repeat('a', 10),
                ],
                'expected' => 'title',
            ],
            'titleが256文字以上' => [
                'data' => [
                    'title' => str_repeat('a', 256),
                    'body' => str_repeat('a', 10),
                ],
                'expected' => 'title',
            ],
            'bodyが空' => [
                'data' => [
                    'title' => str_repeat('a', 10),
                    'body' => '',
                ],
                'expected' => 'body',
            ],
            'bodyが10文字以下' => [
                'data' => [
                    'title' => str_repeat('a', 10),
                    'body' => str_repeat('a', 9),
                ],
                'expected' => 'body',
            ],
        ];
    }

    /**
     * @test
     */
    public function findTagged_特定のタグでフィルターされた検索結果が返却される()
    {
        $query = $this->Articles->find('tagged', ['tags' => 'sample']);

        $this->assertInstanceOf('Cake\ORM\Query', $query);

        $result = $query->enableHydration(false)->toArray();
        $expected = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'First Article',
                'body' => 'First Article Body',
                'published' => 1,
                'created' => new FrozenTime('018-01-07 15:47:01'),
                'slug' => 'first',
            ],
        ];
        $this->assertEquals($expected, $result);
    }
}
