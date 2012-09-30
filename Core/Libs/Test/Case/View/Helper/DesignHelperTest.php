<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('DesignHelper', 'Libs.View/Helper');

/**
 * DesignHelper Test Case
 *
 */
class DesignHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$View = new View();
		$this->Design = new DesignHelper($View);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Design);

		parent::tearDown();
	}

/**
 * testArrayToList method
 *
 * @dataProvider arrayToListDataProvider
 */
	public function testArrayToList($data, $expected) {
		if(isset($data['div'])) {
			$result = $this->Design->arrayToList($data['data'], $data['options'], $data['div']);
		} else {
			$result = $this->Design->arrayToList($data['data'], $data['options']);
		}
		$this->assertTags($result, $expected);
	}

/**
 * @brief array to list data provider
 */
	public function arrayToListDataProvider() {
		return array(
			'plain' => array(
				array(
					'data' => array('a', 'b'),
					'options' => null,
					'div' => false
				),
				array(
					array('ul' => true),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul'
				)
			),
			'with_div' => array(
				array(
					'data' => array('a', 'b'),
					'options' => null,
					'div' => true
				),
				array(
					array('div' => true),
					array('ul' => true),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul',
					'/div'
				)
			),
			'options_div' => array(
				array(
					'data' => array('a', 'b'),
					'options' => array('div' => true)
				),
				array(
					array('div' => true),
					array('ul' => true),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul',
					'/div'
				)
			),
			'div_class' => array(
				array(
					'data' => array('a', 'b'),
					'options' => array('div' => 'foo')
				),
				array(
					array('div' => array('class' => 'foo')),
					array('ul' => true),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul',
					'/div'
				)
			),
			'div_id' => array(
				array(
					'data' => array('a', 'b'),
					'options' => array('div_id' => 'foo')
				),
				array(
					array('div' => array('id' => 'foo')),
					array('ul' => true),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul',
					'/div'
				)
			),
			'ul_class' => array(
				array(
					'data' => array('a', 'b'),
					'options' => 'foo'
				),
				array(
					array('div' => array('class' => 'foo')),
					array('ul' => array('class' => 'foo')),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul',
					'/div'
				)
			),
			'ul_class_no_div' => array(
				array(
					'data' => array('a', 'b'),
					'options' => 'foo', 'div' => false
				),
				array(
					array('ul' => array('class' => 'foo')),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul'
				)
			),
			'ul_class_id' => array(
				array(
					'data' => array('a', 'b'),
					'options' => array('ul' => 'foo', 'ul_id' => 'bar'),
					'div' => false
				),
				array(
					array('ul' => array('class' => 'foo', 'id' => 'bar')),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul'
				)
			),
			'div_class_id_ul_class_id' => array(
				array(
					'data' => array('a', 'b'),
					'options' => array('ul' => 'ul-class', 'ul_id' => 'ul-id', 'div' => 'div-class', 'div_id' => 'div-id'),
				),
				array(
					array('div' => array('class' => 'div-class', 'id' => 'div-id')),
					array('ul' => array('class' => 'ul-class', 'id' => 'ul-id')),
					array('li' => true),
					'a',
					'/li',
					array('li' => true),
					'b',
					'/li',
					'/ul',
					'/div'
				)
			)
		);
	}

/**
 * @brief test tab exception
 *
 * @expectedException InvalidArgumentException
 */
	public function testTabException() {
		$result = $this->Design->tabs(array('a', 'b'), array('A'));
	}

/**
 * testTabs method
 *
 * @dataProvider tabsDataProvider
 */
	public function testTabs($data, $expected) {
		$result = $this->Design->tabs($data['tabs'], $data['content']);
		$this->assertTags($result, $expected);
	}

/**
 * @brief data provider for testing tabs
 *
 * @return array
 */
	public function tabsDataProvider() {
		$linkRegex = 'preg:/#([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})\-%d/';
		$divRegex = 'preg:/([a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12})\-%d/';
		return array(
			'basic' => array(
				array(
					'tabs' => array('A'),
					'content' => array('a')
				),
				array(
					array('div' => array('class' => 'tabs')),
						array('ul' => true),
							array('li' => true),
								array('a' => array('href' => sprintf($linkRegex, 0))),
									'A',
								'/a',
							'/li',
						'/ul',
						array('div' => array('id' => sprintf($divRegex, 0))),
							'a',
						'/div',
					'/div'
				)
			),
			'multi' => array(
				array(
					'tabs' => array(array('text' => 'A'), 'B', 'C'),
					'content' => array('a', 'b', 'c')
				),
				array(
					array('div' => array('class' => 'tabs')),
						array('ul' => true),
							array('li' => true),
								array('a' => array('href' => sprintf($linkRegex, 0))),
									'A',
								'/a',
							'/li',
							array('li' => true),
								array('a' => array('href' => sprintf($linkRegex, 1))),
									'B',
								'/a',
							'/li',
							array('li' => true),
								array('a' => array('href' => sprintf($linkRegex, 2))),
									'C',
								'/a',
							'/li',
						'/ul',
						array('div' => array('id' => sprintf($divRegex, 0))),
							'a',
						'/div',
						array('div' => array('id' => sprintf($divRegex, 1))),
							'b',
						'/div',
						array('div' => array('id' => sprintf($divRegex, 2))),
							'c',
						'/div',
					'/div'
				)
			),
			'custom_links' => array(
				array(
					'tabs' => array(array('text' => 'A', 'config' => array('class' => 'foo'))),
					'content' => array('a')
				),
				array(
					array('div' => array('class' => 'tabs')),
						array('ul' => true),
							array('li' => true),
								array('a' => array('href' => sprintf($linkRegex, 0), 'class' => 'foo')),
									'A',
								'/a',
							'/li',
						'/ul',
						array('div' => array('id' => sprintf($divRegex, 0))),
							'a',
						'/div',
					'/div'
				)
			)
		);
	}

}
