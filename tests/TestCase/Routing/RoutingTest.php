<?php
declare(strict_types=1);

namespace App\Test\TestCase\Routing;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

/**
 * Class RoutingTest
 * @package App\Test\TestCase\Routing
 */
class RoutingTest extends TestCase
{
    /**
     * @test
     *
     * 正引き（'/url' => 配列）
     *
     * @dataProvider dataRouting
     *
     * @param string $url
     * @param array $expected
     * @param array $expected_pass
     *
     * @return void
     */
    public function Route正引き(string $url, array $expected, array $expected_pass)
    {
        $this->markTestSkipped('in progress...');

        $actual = Router::parseRequest(new ServerRequest([
            'url' => $url,
            'environment' => ['REQUEST_METHOD' => 'GET'],
        ]));
        $this->assertSame($expected['controller'], $actual['controller']);
        $this->assertSame($expected['action'], $actual['action']);
        $this->assertSame($expected_pass, $actual['pass']);
    }

    /**
     * @test
     *
     * @dataProvider dataRouting
     *
     * @param string $expected
     * @param array $parseArray
     *
     * @return void
     */
    public function Route逆引き(string $expected, array $parseArray)
    {
        $this->markTestSkipped('in progress...');

        $this->assertSame($expected, Router::url($parseArray));
    }

    public function dataRouting(): array
    {
        return [
            '/' => [
                'url' => '/',
                'expected' => [
                    'controller' => 'Pages',
                    'action' => 'display',
                    'home',
                ],
                'expected_pass' => ['home'],
            ],
            '/articles' => [
                'url' => '/articles',
                'expected' => [
                    'controller' => 'Articles',
                    'action' => 'index',
                ],
                'expected_pass' => [],
            ],
            '/articles/tagged' => [
                'url' => '/articles/tagged',
                'expected' => [
                    'controller' => 'Articles',
                    'action' => 'tags',
                ],
                'expected_pass' => [],
            ],
            '/articles/tagged/funny/cat/gifs' => [
                'url' => '/articles/tagged/funny/cat/gifs',
                'expected' => [
                    'controller' => 'Articles',
                    'action' => 'tags',
                    'funny', 'cat', 'gifs',
                ],
                'expected_pass' => ['funny', 'cat', 'gifs'],
            ],
        ];
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \PHPUnit\Exception
     */
    public function apiプレフィックスRoute正引き()
    {
        $this->markTestSkipped('in progress...');

        $actual = Router::parseRequest(new ServerRequest([
            'url' => '/api/articles',
        ]));
        $this->assertSame('api', $actual['prefix']);
        $this->assertSame('Articles', $actual['controller']);
        $this->assertSame('index', $actual['action']);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \PHPUnit\Exception
     */
    public function apiプレフィックスRoute逆引き()
    {
        $this->markTestSkipped('in progress...');

        $this->assertSame('/api/articles', Router::url([
            'prefix' => 'api',
            'controller' => 'Articles',
            'action' => 'index',
        ]));
    }
}
