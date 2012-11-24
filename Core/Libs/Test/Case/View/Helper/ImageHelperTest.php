<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('ImageHelper', 'Libs.View/Helper');

/**
 * ImageHelper Test Case
 *
 */
class ImageHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Image = new ImageHelper($View);

		$images = array(
			'foo' => array (
				'foo1' => 'foo1.png',
				'foo2' => 'foo2.png',
				'delete' => 'delete.png'
			),
			'bar' => array (
				'bar1' => 'bar1.png',
				'bar2' => 'bar2.png'
			),
			'folders' => array(
				'folder' => 'folder.png',
				'empty' => 'empty.png'
			),
			'unknown' => array(
				'unknown' => 'unknown.png'
			)
		);
		Configure::write('CoreImages.images', $images);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Image);

		parent::tearDown();
	}

/**
 * testImage method
 *
 * @dataProvider imageDataProvider
 */
	public function testImage($data, $expected) {
		$result = $this->Image->image($data['path'], $data['key']);
		$this->assertTags($result, $expected);
	}

/**
 * data provider for testing images
 *
 * @return array
 */
	public function imageDataProvider() {
		return array(
			'simple' => array(
				array('path' => 'foo', 'key' => 'foo1'),
				array(
					array('img' => array(
						'src' => '/img/core/icons/foo/foo1.png',
						'width' => '20px',
						'title' => 'foo1',
						'alt' => 'foo1'
					))
				)
			),
			'missing' => array(
				array('path' => 'fake', 'key' => 'foo1'),
				array(array())
			)
		);
	}

/**
 * testFindByExtention method
 *
 * @dataProvider findByExtentionDataProvider
 */
	public function testFindByExtention($data, $expected) {
		$result = $this->Image->findByExtention($data['ext'], $data['config']);
		$this->assertTags($result, $expected);
	}

/**
 * data provider for testing by extention
 *
 * @return array
 */
	public function findByExtentionDataProvider() {
		return array(
			'empty' => array(
				array('ext' => null, 'config' => array()),
				array(
					array('img' => array(
						'src' => '/img/core/icons/folders/empty.png',
						'width' => '20px',
						'title' => 'folder',
						'alt' => 'folder'
					))
				)
			),
			'foo' => array(
				array('ext' => 'foo1', 'config' => array()),
				array(
					array('img' => array(
						'src' => '/img/core/icons/foo/foo1.png',
						'width' => '20px',
						'title' => 'foo1',
						'alt' => 'foo1'
					))
				)
			),
			'dot_ext' => array(
				array('ext' => '.baz', 'config' => array()),
				array(
					array('img' => array(
						'src' => '/img/core/icons/unknown/unknown.png',
						'width' => '20px',
						'title' => 'unknown',
						'alt' => 'unknown'
					))
				)
			),
			'delete_niceTitleText' => array(
				array('ext' => 'delete', 'config' => array()),
				array(
					array('img' => array(
						'src' => '/img/core/icons/foo/delete.png',
						'width' => '20px',
						'title' => 'Delete some  :: Tick the checkboxes next to the  you want to delete then click here.&lt;br/&gt;If possible the  will be moved to the trash can. If not they will be deleted permanently.',
						'alt' => 'Delete some'
					))
				)
			)
		);
	}

/**
 * testGetRelativePath method
 *
 * @return void
 */
	public function testGetRelativePath() {
		$expected = 'core/icons/foo/foo1.png';
		$result = $this->Image->getRelativePath('foo', 'foo1');
		$this->assertEquals($expected, $result);
		$this->assertEmpty($this->Image->errors);

		$expected = 'core/icons/bar/bar1.png';
		$result = $this->Image->getRelativePath('bar', 'bar1');
		$this->assertEquals($expected, $result);
		$this->assertEmpty($this->Image->errors);

		$result = $this->Image->getRelativePath('bar', 'foo1');
		$this->assertFalse($result);

		$expected = array('CoreImages.images.bar.foo1 does not exist');
		$this->assertEquals($expected, $this->Image->errors);
	}

/**
 * test checking places exist
 *
 * @return void
 */
	public function testPlaceExists() {
		$expected = array('foo', 'bar');
		$result = $this->Image->placeExists(array('foo', 'bar'));
		$this->assertEquals($expected, $result);
		$this->assertEmpty($this->Image->errors);

		$expected = array('foo');
		$result = $this->Image->placeExists(array('foo', 'baz'));
		$this->assertEquals($expected, $result);
		$this->assertEmpty($this->Image->errors);

		$expected = array('foo');
		$result = $this->Image->placeExists('foo');
		$this->assertEquals($expected, $result);
		$this->assertEmpty($this->Image->errors);

		$result = $this->Image->placeExists(array('baz'));
		$this->assertFalse($result);

		$expected = array('the place(s) does not exist.');
		$this->assertEquals($expected, $this->Image->errors);
	}

/**
 * test getting image
 */
	public function testGetImages() {
		$expected = array(
			'foo' => array (
				'foo1' => 'foo1.png',
				'foo2' => 'foo2.png',
				'delete' => 'delete.png'
			),
			'bar' => array (
				'bar1' => 'bar1.png',
				'bar2' => 'bar2.png'
			),
			'folders' => array(
				'folder' => 'folder.png',
				'empty' => 'empty.png'
			),
			'unknown' => array(
				'unknown' => 'unknown.png'
			)
		);
		$result = $this->Image->getImages();
		$this->assertEquals($expected, $result);
	}

/**
 * test getting places
 */
	public function testGetPlaces() {
		$expected = array('foo', 'bar', 'folders', 'unknown');
		$result = $this->Image->getPlaces();
		$this->assertEquals($expected, $result);
	}

/**
 * test exists
 *
 * @dataProvider existsDataProvider
 */
	public function testExists($data, $expected) {
		foreach ($expected as $returnType => $expect) {
			$result = $this->Image->exists($data['place'], $data['key'], $returnType);
			$this->assertEquals($expect, $result);
			$this->assertEmpty($this->Image->errors);
		}
	}

/**
 * data provider for existing images
 * @return type
 */
	public function existsDataProvider() {
		return array(
			array(
				array('place' => 'foo', 'key' => 'foo1'),
				array(
					'fileName' => 'foo1.png',
					'relativePath' => 'core/icons/foo/foo1.png',
					'absolutePath' => true
				)
			),
			array(
				array('place' => 'bar', 'key' => 'bar1'),
				array(
					'fileName' => 'bar1.png',
					'relativePath' => 'core/icons/bar/bar1.png',
					'absolutePath' => true
				)
			),
		);
	}

/**
 * test non existant
 */
	public function testNonExists() {
		$result = $this->Image->exists('foo', 'bar1');
		$this->assertFalse($result);

		$expected = array('CoreImages.images.foo.bar1 does not exist');
		$this->assertEquals($expected, $this->Image->errors);
	}

}
