<?php
	App::import('Core', array('Helper', 'AppHelper', 'ClassRegistry', 'Controller', 'Model'));
	App::import('Libs', array('Charts.BaseChartEngine'));
	App::import('Helper', array('Html', 'Google.GoogleStaticChartEngine'));

	/**
	 * TheGoogleStaticChartEngineTestController class
	 */
	class TheGoogleStaticChartEngineTestController extends Controller {

	/**
	 * name property
	 *
	 * @var string 'TheTest'
	 * @access public
	 */
		var $name = 'TheTest';

	/**
	 * uses property
	 *
	 * @var mixed null
	 * @access public
	 */
		var $uses = null;
	}

	Mock::generate('View', 'HtmlHelperMockView');

	/**
	 * HtmlHelperTest class
	 *
	 * @package       cake
	 * @subpackage    cake.tests.cases.libs.view.helpers
	 */
	class GoogleStaticChartEngineHelperTest extends CakeTestCase {
		/**
		 * html property
		 *
		 * @var object
		 * @access public
		 */
		public $GoogleStaticChartEngine = null;

		/**
		 * setUp method
		 *
		 * @access public
		 * @return void
		 */
		function startTest() {
			$this->GoogleStaticChartEngine =& new GoogleStaticChartEngineHelper();
			$view =& new View(new TheGoogleStaticChartEngineTestController());
			ClassRegistry::addObject('view', $view);
		}

		/**
		 * endTest method
		 *
		 * @access public
		 * @return void
		 */
		function endTest() {
			ClassRegistry::flush();
			unset($this->GoogleStaticChartEngine);
		}

		/**
		 * @brief test the implod method
		 */
		public function testFormatImplode(){
			$this->GoogleStaticChartEngine->setType('line');

			$expected = '1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_implode('data', array(1,2,3,4,5)));

			$expected = '1|2|3|4|5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_implode('labels', array(1,2,3,4,5)));

			$expected = '1x2x3x4x5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_implode('size', array(1,2,3,4,5)));

			$this->expectError('Value for size is type string and expecting array');
			$this->GoogleStaticChartEngine->_implode('size', 'string');

			$this->expectError('No format available for foo');
			$this->GoogleStaticChartEngine->_implode('foo', array(1,2,3));

			$this->expectError('Array to string conversion');
			$this->GoogleStaticChartEngine->_implode('size', array(array(1,2,3)));
		}

		/**
		 * @brief test the generic query string builder
		 */
		public function testFormatGeneric(){
			$this->GoogleStaticChartEngine->setType('line');

			$expected = 'chd=t:1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatGeneric('data', array(1,2,3,4,5)));

			$expected = 'chd=t:';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatGeneric('data', array()));

			$expected = 'chs=100x200';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatGeneric('size', array(100,200)));

			$this->expectError();
			$this->GoogleStaticChartEngine->_formatGeneric('data', 'string');

			$this->expectError();
			$this->GoogleStaticChartEngine->_formatGeneric('data', false);
		}

		/**
		 * @brief test building the query data for the chart data
		 */
		public function testFormatData(){
			$this->GoogleStaticChartEngine->setType('line');

			$expected = 'chd=t:1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatData(array(1,2,3,4,5)));

			$expected = 'chd=t:1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatData(array(array(1,2,3,4,5))));

			$expected = 'chd=t:1,2,3,4,5|1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatData(array(array(1,2,3,4,5), array(1,2,3,4,5))));

			$expected = 'chd=t:1,2,3,4,5|1,2,3,4,5|1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatData(array(array(1,2,3,4,5), array(1,2,3,4,5), array(1,2,3,4,5))));
		}

		/**
		 * @brief test building the query string for chart axes lables
		 */
		public function testFormatLabels(){
			$this->GoogleStaticChartEngine->setType('line');

			$expected = array(
				'labels' => 'chxl=0:|abc|xyz|1:|1|2|3|4|5|6',
				'axes' => 'chxt=x,y'
			);
			$data = array(
				'x' => array('abc', 'xyz'),
				'y' => array(1,2,3,4,5,6)
			);
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLabels($data));
		}

		/**
		 * @brief check building the query string for coloring charts
		 */
		public function testFormatColor(){
			$this->GoogleStaticChartEngine->setType('line');

			$data = array('series' => '03348a');
			$expected = array('chco=03348a');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatColor($data));

			$data = array(
				'series' => array(
					'0d5c05', // dark green
					'03348a' // dark blue
				)
			);
			$expected = array('chco=0d5c05,03348a');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatColor($data));

			$data = array(
				'foo' => 'bar',
				'series' => array(
					'0d5c05', // dark green
					'03348a' // dark blue
				)
			);
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatColor($data));

			$data = array('series' => array());
			$this->assertEqual(array(), $this->GoogleStaticChartEngine->_formatColor($data));
		}

		/**
		 * @brief check building the query string for spacing on charts
		 */
		public function testFormatSpacing(){
			$this->GoogleStaticChartEngine->setType('line');

			$data = array('type' => 'absolute', 'width' => 10);
			$expected = 'chbh=10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'relative', 'width' => 10);
			$expected = 'chbh=10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'absolute', 'padding' => 10);
			$expected = 'chbh=a,10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'relative', 'padding' => 10);
			$expected = 'chbh=r,0.1';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'absolute');
			$expected = 'chbh=a';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'foo', 'padding' => 10);
			$this->expectError('Spacing type should be absolute or relative, not foo');
			$this->GoogleStaticChartEngine->_formatSpacing($data);
		}

		/**
		 * @brief test building the spacing on group type charts
		 */
		public function testFormatSpacingForGroupCharts(){
			$this->GoogleStaticChartEngine->setType('bar_group');

			$data = array('type' => 'absolute', 'padding' => 10);
			$expected = 'chbh=a,10,10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('padding' => 10);
			$expected = 'chbh=a,10,10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'relative', 'padding' => 10);
			$expected = 'chbh=r,0.1,0.1';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));
		}

		/**
		 * @brief test building the size query string
		 */
		public function testFormatSize(){
			$this->GoogleStaticChartEngine->setType('line');

			$data = array('width' => 200, 'height' => 400);
			$expected = 'chs=200x400';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSize($data));

			$data = array('width' => 400, 'height' => 200);
			$expected = 'chs=400x200';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSize($data));

			$data = array('width' => 200);
			$expected = 'chs=200x200';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSize($data));

			$data = array('width' => 600, 'height' => 200, 'foo' => 123);
			$expected = 'chs=600x200';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSize($data));

			$data = array('width' => 600, 'height' => 600);
			$this->expectError('Size of 360000px is greater than maximum allowed size 300000px');
			$this->GoogleStaticChartEngine->_formatSize($data);

			$data = array('width' => 300000, 'height' => 1);
			$expected = 'chs=300000x1';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSize($data));

			$data = array('width' => 300001, 'height' => 1);
			$this->expectError('Size of 300001px is greater than maximum allowed size 300000px');
			$this->GoogleStaticChartEngine->_formatSize($data);
		}

		/**
		 * @brief test generating a legend
		 */
		public function testFormatLegend(){
			$this->GoogleStaticChartEngine->setType('line');

			$data = array('position' => 'top', 'order' => 'auto', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=t|a');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('position' => 'top', 'order' => 'auto');
			$this->expectError('Skipping legend, no lables specified');
			$this->GoogleStaticChartEngine->_formatLegend($data);

			$data = array('labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=r|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			/**
			 * Test the ordering
			 */
			$data = array('order' => 'default', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=r|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('order' => 'reverse', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=r|r');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('order' => array(1,0), 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=r|1,0');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('order' => array(1,0), 'labels' => array('abc', 'xyz', 'foo'));
			$expected = array('legend_labels' => 'chdl=abc|xyz|foo','legend_position' => 'chdlp=r|l');
			$this->expectError();
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			/**
			 * test the position
			 */
			$data = array('position' => 'bottom_horizontal', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=b|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));
			
			$data = array('position' => 'bottom', 'labels' => array('abc', 'xyz'));
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));
			
			$data = array('position' => 'bottom_vertical', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=bv|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('position' => 'top', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=t|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('position' => 'top_horizontal', 'labels' => array('abc', 'xyz'));
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('position' => 'top_vertical', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=tv|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('position' => 'left', 'labels' => array('abc', 'xyz'));
			$expected = array('legend_labels' => 'chdl=abc|xyz','legend_position' => 'chdlp=l|l');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));

			$data = array('position' => 'left_vertical', 'labels' => array('abc', 'xyz'));
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLegend($data));
		}

		/**
		 * @brief test building the different parts of a query
		 */
		public function testFormatFormatQueryParts(){
			$this->GoogleStaticChartEngine->setType('line');
			$data = array();

			$data['data'] = array(array(1,2,3,4,5), array(1,2,3,4,5));
			$expected = 'chd=t:1,2,3,4,5|1,2,3,4,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatQueryParts('data', $data['data']));

			$data['size'] = array('width' => 123, 'height' => 321);
			$expected = 'chs=123x321';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatQueryParts('size', $data['size']));

			$data['color'] = array('series' => array('0000ff'));
			$expected = array(0 => 'chco=0000ff');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatQueryParts('color', $data['color']));

			$data['scale'] = array(0, 300);
			$expected = 'chds=0,300';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatQueryParts('scale', $data['scale']));

			$this->assertFalse($this->GoogleStaticChartEngine->_formatQueryParts('data', array()));
		}

		/**
		 * @brief test for charts that will work
		 */
		public function testFormatBuildChart(){
			$this->GoogleStaticChartEngine->setType('line');

			$chart = array(
				'data' => array(1,2,3,4,5),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321),
				'extra' => array('return' => 'url')
			);

			/**
			 * test building a query
			 */
			$expected = 'http://0.chart.apis.google.com/chart?cht=lc&chd=t:1,2,3,4,5&chco=123123&chs=123x321';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));

			/**
			 * check that the urls are correct for using downloading many images at once
			 */
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);
			$this->GoogleStaticChartEngine->_buildChart($chart);

			$expected = 'http://9.chart.apis.google.com/chart?cht=lc&chd=t:1,2,3,4,5&chco=123123&chs=123x321';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));

			$expected = 'http://0.chart.apis.google.com/chart?cht=lc&chd=t:1,2,3,4,5&chco=123123&chs=123x321';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));

			$chart = array(
				'data' => array(1,2,3,4,5),
				'values' => array('min' => 1, 'max' => 5),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321),
				'extra' => array('return' => 'url', 'scale' => 'relative')
			);
			$expected = 'http://1.chart.apis.google.com/chart?cht=lc&chd=t:1,2,3,4,5&chco=123123&chs=123x321&chds=0,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));

			$this->GoogleStaticChartEngine->setType('bar');
			$chart = array(
				'data' => array(1,2,3,4,5),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321),
				'config' => array('type' => 'horizontal_group'),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://2.chart.apis.google.com/chart?cht=bhg&chd=t:1,2,3,4,5&chs=123x321';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));
		}

		/**
		 * @brief tests for charts that will not work
		 */
		public function testBrokenCharts(){
			$this->GoogleStaticChartEngine->setType('line');
			$chart = array(
				'data' => array(
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
					1,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,2,3,4,5,
				),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321),
				'extra' => array('return' => 'url')
			);
			$this->expectError('The query string is too long (2809 chars)');
			$this->GoogleStaticChartEngine->_buildChart($chart);

			$this->GoogleStaticChartEngine->setType('foo');
			$chart = array(
				'data' => array(1,2,3,4,5),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321)
			);
			$this->expectError('The chart type "foo" is invalid');
			$this->GoogleStaticChartEngine->_buildChart($chart);
		}

		public function testFormatImage(){
		}
}
