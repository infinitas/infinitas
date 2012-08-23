<?php
class BootstrapFunctionsTest extends CakeTestCase {
/**
 * @brief test that lcfirst works when not available from php
 *
 * @return void
 */
	public function testLcFirst() {
		$this->skipIf(defined('INFINITAS_FUNCTION_LCFIRST'));

		$this->assertEquals('home', lcfirst('Home'));
		$this->assertEquals('home', lcfirst('home'));
		$this->assertEquals('123home', lcfirst('123home'));
	}

/**
 * @brief test that cache configurations are created properly from event data
 *
 * @return void
 */
	public function testConfigureCache() {
		foreach(Cache::configured() as $cache) {
			Cache::drop($cache);
		}

		$this->assertEquals(array(), Cache::configured());
		$data = array(
			'setupCache' => array(
				'SomePlugin' => array(
					'name' => 'some-plugin',
					'config' => array(
						'prefix' => 'some.prefix',
					)
				)
			)
		);
		$expected = array('some-plugin');

		configureCache($data);
		$this->assertEquals($expected, Cache::configured());


		$data = array(
			'setupCache' => array(
				'AnotherPlugin' => array(
					'name' => 'another-plugin',
					'config' => array(
						'prefix' => 'another.prefix',
					)
				)
			)
		);
		$cacheFolder = CACHE . 'plugins' . DS . 'another' . DS . 'prefix';
		$expected = array('some-plugin', 'another-plugin');

		if(is_dir($cacheFolder)) {
			rmdir($cacheFolder);
		}
		$this->assertFalse(is_dir($cacheFolder));

		Configure::write('Cache.engine', 'Libs.NamespaceFile');
		configureCache($data);
		$this->assertEquals($expected, Cache::configured());
		$this->assertTrue(is_dir($cacheFolder));
		rmdir($cacheFolder);
	}

/**
 * @brief test cache prefixes for multi sites on one host
 *
 * @return void
 */
	public function testCachePrefix() {
		$this->assertEquals(
			substr(sha1(env('DOCUMENT_ROOT') . env('HTTP_HOST')), 0, 10),
			INFINITAS_CACHE_PREFIX
		);
	}

/**
 * @brief test cache name generation
 *
 * @dataProvider cacheNames
 * @return void
 */
	public function testCacheName($data, $expected) {
		$this->assertEquals($expected, cacheName('test', $data));
	}

/**
 * cacheNames data provider
 *
 * @return void
 */
	public function cacheNames() {
		return array(
			array('', 'test'),
			array(false, 'test'),
			array(null, 'test'),
			array(99999, 'test_8beff075156468520fcc1ec1fc21ec3ebe7094fb'),
			array(array(99999), 'test_99b7ae928417805395658a374af641d8eb825a96'),
			array(serialize(array(99999)), 'test_2de51ac49cddd4f8ced453ad5238f630614cde9c')
		);
	}

/**
 * @brief test cache name generation
 *
 * @dataProvider prettyNames
 * @return void
 */
	public function testPrettyName($data, $expected) {
		$this->assertEquals($expected, prettyName($data));
	}

/**
 * prettyNames data provider
 *
 * @return void
 */
	public function prettyNames() {
		return array(
			array('Post', 'Posts'),
			array('AwesomeModel', 'Awesome models'),
			array('', ''),
			array(false, ''),
			array(array(), 'Arrays'),
		);
	}

/**
 * route debug
 */
	public function testDebugRoute() {
		$route = array(
			'Route' => array(
				'url' => '/some/url',
				'values' => array(
					'something' => 'foo',
					'anotherthing' => 'bar'
				),
				'regex' => array(
					'test' => '/[0-9].*/'
				)
			)
		);
		$expected = "InfinitasRouter::connect('/some/url', array('something' => 'foo', 'anotherthing' => 'bar'), array('test' => '/[0-9].*/'));";
		$result = debugRoute($route);

		$this->assertEquals($expected, $result);
		$route = array(
			'Route' => array(
				'url' => '/some/url',
				'values' => array(
					'something' => 'foo',
					'anotherthing' => 'bar'
				),
				'regex' => array(
				)
			)
		);
		$expected = "InfinitasRouter::connect('/some/url', array('something' => 'foo', 'anotherthing' => 'bar'));";
		$result = debugRoute($route);
		$this->assertEquals($expected, $result);
	}

/**
 * @brief test convert
 */
	public function testConvert() {
		$expected = '0 b';
		$result = convert(0);
		$this->assertEquals($expected, $result);

		$expected = '1 b';
		$result = convert(1);
		$this->assertEquals($expected, $result);

		$expected = '1 kb';
		$result = convert(1024);
		$this->assertEquals($expected, $result);

		$expected = '1 mb';
		$result = convert(1024 * 1024);
		$this->assertEquals($expected, $result);

		$expected = '1 gb';
		$result = convert(1024 * 1024 * 1024);
		$this->assertEquals($expected, $result);

		$expected = '1 tb';
		$result = convert(1024 * 1024 * 1024 * 1024);
		$this->assertEquals($expected, $result);

		$expected = '1 pb';
		$result = convert(1024 * 1024 * 1024 * 1024 * 1024);
		$this->assertEquals($expected, $result);
	}
}
