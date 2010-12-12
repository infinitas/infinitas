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
			$this->GoogleStaticChartEngine = new GoogleStaticChartEngineHelper();
			$this->GoogleStaticChartEngine->Html = new HtmlHelper();
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

			$data = array('series' => array(array('000000'), array('FF0000', '00FF00', '0000FF')));
			$expected = array('chco=000000,FF0000|00FF00|0000FF');
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatColor($data));
		}

		/**
		 * @todo 
		 */
		public function testFormatColorFill(){
			
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
			$expected = 'chbh=a,10,20';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('padding' => 10);
			$expected = 'chbh=a,10,20';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatSpacing($data));

			$data = array('type' => 'relative', 'padding' => 10);
			$expected = 'chbh=r,0.1,0.2';
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
		 * @brief test building the query string for line styles
		 */
		public function testFormatLineStyle(){
			$this->GoogleStaticChartEngine->setType('gauge');

			$data = array(
				array('thickness' => 3,'arrow' => 15),
				array('thickness' => 3,'dash' => array(5, 5),'arrow' => 10)
			);
			$expected = 'chls=3|3,5,5|15|10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLineStyle($data));

			$data = array(
				array('thickness' => 3),
				array('thickness' => 3,'dash' => array(5, 5))
			);
			$expected = 'chls=3|3,5,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLineStyle($data));

			$data = array(
				array('thickness' => 3),
				array('thickness' => 3,'dash' => array(5))
			);
			$expected = 'chls=3|3,5,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLineStyle($data));

			$data = array(
				array('thickness' => 3),
				array('thickness' => 3,'dash' => 5)
			);
			$expected = 'chls=3|3,5,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLineStyle($data));

			$data = array(
				array('thickness' => 3),
				array('thickness' => 3,'dash' => array(5, 5, 5, 5, 5))
			);
			$expected = 'chls=3|3,5,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLineStyle($data));

			$data = array(
				array('thickness' => 3, 'dash' => array(5, 5)),
				array('thickness' => 3, 'dash' => array(5, 5))
			);
			$expected = 'chls=3,5,5|3,5,5';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_formatLineStyle($data));
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
			$expected = 'http://2.chart.apis.google.com/chart?cht=bhg&chd=t:1,2,3,4,5&chco=123123&chs=123x321';
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

		/**
		 * @brief test building image markup for charts
		 */
		public function testFormatImage(){
			$this->GoogleStaticChartEngine->setType('line');

			$chart = array(
				'data' => array(1,2,3,4,5),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321)
			);
			$expected = '<img src="http://0.chart.apis.google.com/chart?cht=lc&chd=t:1,2,3,4,5&chco=123123&chs=123x321" alt="" />';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));

			$chart = array(
				'data' => array(1,2,3,4,5),
				'color' => array('series' => array('123123')),
				'size' => array('width' => 123, 'height' => 321),
				'extra' => array(
					'image' => array(
						'class' => 'something',
						'alt' => 'some chart'
					)
				)
			);
			$expected = '<img src="http://1.chart.apis.google.com/chart?cht=lc&chd=t:1,2,3,4,5&chco=123123&chs=123x321" class="something" alt="some chart" />';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->_buildChart($chart));
		}

		/**
		 * @brief test building different types of gauge charts
		 */
		public function testBuildGaugeCharts(){
			$chart = array(
				'data' => array(70),
				'axes' => array(
					'x',
					'y'
				),
				'labels' => array(
					array('Groovy'),
					array('slow', 'faster', 'crazy')
				),
				'color' => array('series' => explode(',', 'FF0000,FF8040,FFFF00,00FF00,00FFFF,0000FF,800080')),
				'size' => array('width' => 200, 'height' => 125),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://0.chart.apis.google.com/chart?cht=gom&chd=t:70&chxl=0:|Groovy|1:|slow|faster|crazy&chxt=0,1&chco=FF0000,FF8040,FFFF00,00FF00,00FFFF,0000FF,800080&chs=200x125';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->gauge($chart));

			$chart = array(
				'data' => array(20, 40, 60),
				'legend' => array('position' => 'bottom_horizontal', 'labels' => array(1, 2, 3)),
				'size' => array('width' => 150, 'height' => 100),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://1.chart.apis.google.com/chart?cht=gom&chd=t:20,40,60&chdl=1|2|3&chdlp=b|l&chs=150x100';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->gauge($chart));

			$chart = array(
				'data' => array(array(20, 40), array(60)),
				'size' => array('width' => 150, 'height' => 100),
				'extra' => array(
					'return' => 'url',
					'arrows' => array(
						array('thickness' => 3, 'arrow' => 15),
						array('thickness' => 3, 'dash' => array(5, 5), 'arrow' => 10)
					)
				)
			);
			$expected = 'http://2.chart.apis.google.com/chart?cht=gom&chd=t:20,40|60&chs=150x100&chls=3|3,5,5|15|10';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->gauge($chart));
		}

		/**
		 * @brief test building bra charts
		 */
		public function testBuildBarCharts(){
			$chart = array(
				'data' => array(12, 23, 10, 40),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'horizontal'),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://0.chart.apis.google.com/chart?cht=bhs&chd=t:12,23,10,40&chs=200x125';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '10,50,60,80,40'), explode(',', '50,60,100,40,20')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'horizontal'),
				'color' => array('series' => array('4d89f9', 'c6d9fd')),
				'spacing' => array('width' => 20),
				'scale' => array(0, 160),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://1.chart.apis.google.com/chart?cht=bhs&chd=t:10,50,60,80,40|50,60,100,40,20&chs=200x125&chco=4d89f9,c6d9fd&chbh=20&chds=0,160';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '10,50,60,80,40'), explode(',', '50,60,100,40,20')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'vertical'),
				'color' => array('series' => array('4d89f9', 'c6d9fd')),
				'spacing' => array('width' => 20),
				'scale' => array(0, 160),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://2.chart.apis.google.com/chart?cht=bvs&chd=t:10,50,60,80,40|50,60,100,40,20&chs=200x125&chco=4d89f9,c6d9fd&chbh=20&chds=0,160';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '10,50,60,80,40'), explode(',', '50,60,100,40,20'), explode(',', '30,30,75,20,60')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'vertical_overlay'),
				'color' => array('series' => array('4d89f9', 'c6d9fd', '0000FF')),
				'labels' => array('y' => range(0, 100, 20)),
				'spacing' => array('width' => 20),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://3.chart.apis.google.com/chart?cht=bvo&chd=t:10,50,60,80,40|50,60,100,40,20|30,30,75,20,60&chs=200x125&chco=4d89f9,c6d9fd,0000FF&chxl=0:|0|20|40|60|80|100&chxt=y&chbh=20';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '60,74'), explode(',', '80,86')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'horizontal_group'),
				'color' => array('series' => array('4d89f9', 'c6d9fd')),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://4.chart.apis.google.com/chart?cht=bhg&chd=t:60,74|80,86&chs=200x125&chco=4d89f9,c6d9fd';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '60,74'), explode(',', '80,86')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'horizontal_group'),
				'color' => array('series' => array('4d89f9', 'c6d9fd')),
				'spacing' => array('width' => 10, 'padding' => 5, 'grouping' => 20),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://5.chart.apis.google.com/chart?cht=bhg&chd=t:60,74|80,86&chs=200x125&chco=4d89f9,c6d9fd&chbh=10,5,20';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '60,74,123,23,45')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'vertical'),
				'color' => array('series' => array(explode('|', 'FFC6A5|FFFF42|DEF3BD|00A5C6|DEBDDE'))),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://6.chart.apis.google.com/chart?cht=bvs&chd=t:60,74,123,23,45&chs=200x125&chco=FFC6A5|FFFF42|DEF3BD|00A5C6|DEBDDE';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '40,13,35'), explode(',', '60,23,45')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'horizontal'),
				'color' => array('series' => array(array('000000'), explode('|', 'FF0000|00FF00|0000FF'))),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://7.chart.apis.google.com/chart?cht=bhs&chd=t:40,13,35|60,23,45&chs=200x125&chco=000000,FF0000|00FF00|0000FF';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(explode(',', '40,13,35'), explode(',', '60,23,45')),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'horizontal'),
				'color' => array('series' => array(explode('|', '0000FF|FF0000|00FF00'), explode('|', 'FF0000|00FF00|0000FF'))),
				'extra' => array('return' => 'url')
			);
			$expected = 'http://8.chart.apis.google.com/chart?cht=bhs&chd=t:40,13,35|60,23,45&chs=200x125&chco=0000FF|FF0000|00FF00,FF0000|00FF00|0000FF';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));
		}

		/**
		 * @brief test bar chart labels
		 */
		public function testBuildBarChartsLables(){
			$chart = array(
				'data' => array(12, 23, 10, 40, 69),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'vertical'),
				'extra' => array('return' => 'url'),
				'labels' => array(
					'x' => range(0, 4),
					'y' => range(0,100, 20)
				)
			);
			$expected = 'http://0.chart.apis.google.com/chart?cht=bvs&chd=t:12,23,10,40,69&chs=200x125&chxl=0:|0|1|2|3|4|1:|0|20|40|60|80|100&chxt=x,y';			
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(12, 23, 10, 40, 69),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'vertical'),
				'extra' => array('return' => 'url'),
				'labels' => array(
					'x' => range('a', 'e'),
					'y' => range(0,100, 20)
				)
			);
			$expected = 'http://1.chart.apis.google.com/chart?cht=bvs&chd=t:12,23,10,40,69&chs=200x125&chxl=0:|a|b|c|d|e|1:|0|20|40|60|80|100&chxt=x,y';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));

			$chart = array(
				'data' => array(12, 23, -50, 40, -15),
				'size' => array('width' => 200, 'height' => 125),
				'config' => array('type' => 'vertical'),
				'extra' => array('return' => 'url'),
				'labels' => array(
					'x' => range('a', 'e'),
					'y' => range(-30,80, 20)
				),
				'scale' => array(-50, 80)
			);
			$expected = 'http://2.chart.apis.google.com/chart?cht=bvs&chd=t:12,23,-50,40,-15&chs=200x125&chxl=0:|a|b|c|d|e|1:|-30|-10|10|30|50|70&chxt=x,y&chds=-50,80';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->bar($chart));
		}

		/**
		 * @brief test line charts
		 */
		public function testBuildLineCharts(){
			$chart = array(
				'data' => explode(',', '40,60,60,45,47,75,70,72'),
				'size' => array('width' => 200, 'height' => 125),
				'extra' => array('return' => 'url'),
			);
			$expected = 'http://0.chart.apis.google.com/chart?cht=lc&chd=t:40,60,60,45,47,75,70,72&chs=200x125';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->line($chart));

			$chart = array(
				'data' => explode(',', '27,25,60,31,25,39,25,31,26,28,80,28,27,31,27,29,26,35,70,25'),
				'size' => array('width' => 200, 'height' => 125),
				'color' => array('series' => '0077CC'),
				'config' => array('type' => 'spark'),
				'extra' => array('return' => 'url'),
			);
			$expected = 'http://1.chart.apis.google.com/chart?cht=ls&chd=t:27,25,60,31,25,39,25,31,26,28,80,28,27,31,27,29,26,35,70,25&chs=200x125&chco=0077CC';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->line($chart));

			$chart = array(
				'data' => array(explode(',', '10,20,40,80,90,95,99'), explode(',', '20,30,40,50,60,70,80'), array(-1), explode(',', '5,10,22,35,85')),
				'size' => array('width' => 200, 'height' => 125),
				'color' => array('series' => '0077CC'),
				'config' => array('type' => 'xy'),
				'color' => array('series' => explode(',', '3072F3,ff0000,00aaaa')),
				'legend' => array(
					'labels' => array('Ponies', 'Unicorns'),
					'position' => 'top'
				),
				'extra' => array(
					'return' => 'url',
					'line_style' => array(array('thickness' => 2, 'dash' => array(4, 1)))
				),
			);
			$expected = 'http://2.chart.apis.google.com/chart?cht=lxy&chd=t:10,20,40,80,90,95,99|20,30,40,50,60,70,80|-1|5,10,22,35,85&chs=200x125&chco=3072F3,ff0000,00aaaa&chdl=Ponies|Unicorns&chdlp=t|l';
			$this->assertEqual($expected, $this->GoogleStaticChartEngine->line($chart));
		}
	}