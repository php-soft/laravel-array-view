<?php

class FactoryTest extends \Orchestra\Testbench\TestCase
{
    public static $objects = array();

    public static function setUpBeforeClass()
    {
        $author = new stdClass();
        $author->name = 'John Doe';
        $author->gender = 'male';

        $article = new stdClass();
        $article->title = 'Example Title';
        $article->author = $author;
        self::$objects['article'] = $article;
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('view.paths', [ __DIR__ . '/../views' ]);
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'PhpSoft\ArrayView\Providers\ArrayViewServiceProvider',
        ];
    }
    /**
     * Get package aliases.  In a normal app environment these would be added to
     * the 'aliases' array in the config/app.php file.  If your package exposes an
     * aliased facade, you should add the alias here, along with aliases for
     * facades upon which your package depends, e.g. Cartalyst/Sentry.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'ArrayView' => 'PhpSoft\ArrayView\Facades\ArrayView',
        ];
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage View [viewNotFound] not found
     */
    public function testViewNotFound()
    {
        $results = arrayView('viewNotFound');
    }

    /**
     * ============================ Test Set Method ============================
     */

    public function testSetValueToKey()
    {
        $results = arrayView('testSet/setValue', array(
            'title'   => 'Example',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('Example', $results['title']);
        $this->assertArrayHasKey('version', $results);
        $this->assertEquals('1.0', $results['version']);
    }

    public function testSetValueIsFunction()
    {
        $results = arrayView('testSet/setFunction', array(
            'author'   => 'John Doe',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals('John Doe', $results['author']['name']);
        $this->assertArrayHasKey('location', $results['author']);
        $this->assertEquals('en', $results['author']['location']);
    }

    public function testSetWithoutKey()
    {
        $results = arrayView('testSet/setWithoutKey', array(
            'author'   => 'John Doe',
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('author', $results);
        $this->assertEquals('John Doe', $results['author']);
    }

    public function testSetUseObject()
    {
        $results = arrayView('testSet/article', array(
            'article'   => self::$objects['article'],
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals(self::$objects['article']->title, $results['title']);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals(self::$objects['article']->author->name, $results['author']['name']);
    }

    /**
     * ============================ Test Each Method ============================
     */

    public function testEachWithEmptyArray()
    {
        $results = arrayView('testEach/test', [ 'numbers' => [] ]);
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('numbers', $results);
        $this->assertInternalType('array', $results['numbers']);
        $this->assertEquals(0, count($results['numbers']));
    }

    public function testEachWithArray()
    {
        $results = arrayView('testEach/test', [ 'numbers' => ['one', 'two'] ]);
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('numbers', $results);
        $this->assertInternalType('array', $results['numbers']);
        $this->assertEquals(2, count($results['numbers']));
        $this->assertArrayHasKey('number', $results['numbers'][0]);
        $this->assertEquals('one', $results['numbers'][0]['number']);
        $this->assertArrayHasKey('number', $results['numbers'][1]);
        $this->assertEquals('two', $results['numbers'][1]['number']);
    }

    /**
     * ============================ Test Partial Method ============================
     */

    public function testPartial()
    {
        $results = arrayView('testPartial/article', array(
            'article'   => self::$objects['article'],
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals(self::$objects['article']->title, $results['title']);
        $this->assertArrayHasKey('author', $results);
        $this->assertArrayHasKey('name', $results['author']);
        $this->assertEquals(self::$objects['article']->author->name, $results['author']['name']);
        $this->assertArrayHasKey('gender', $results['author']);
        $this->assertEquals(self::$objects['article']->author->gender, $results['author']['gender']);
    }

    /**
     * ============================ Test Helper Method ============================
     */

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage View [abcdef] not found.
     */
    public function testHelperNotFound()
    {
        $results = arrayView('testHelper/testNotFound', array(
            'title' => 'example title'
        ));
    }

    public function testHelper()
    {
        $results = arrayView('testHelper/test', array(
            'title' => 'example title'
        ));
        $this->assertNotEmpty($results);
        $this->assertArrayHasKey('title', $results);
        $this->assertEquals('EXAMPLE TITLE', $results['title']);
    }

    /**
     * @expectedException BadFunctionCallException
     */
    public function testHelperInvalid()
    {
        $results = arrayView('testHelper/testHelperInvalid', array(
            'title' => 'example title'
        ));
    }
}
