<?php
	class ChartsHelper extends AppHelper{
		/**
		 * the engine to use when rendering the chart
		 * 
		 * @var string
		 * @access public
		 */
		public $engine = null;

		/**
		 * The raw chart data.
		 *
		 * @var array
		 * @access public
		 */
		public $data = array();

		public $normalize = true;

		/**
		 * Current Javascript Engine that is being used
		 *
		 * @var string
		 * @access private
		 */
		public $__engineName = null;

		/**
		 * Defaults for the charts
		 *
		 * @var array
		 * @access private
		 */
		private $__defaults = array(
			'title' => 'Chart Title',
			'width' => 640,
			'height' => 480,
			'type' => array(),
			'color' => array(
				'background' => 'FFFFFF',
				'fill' => 'FFCC33',
				'text' => '989898',
				'lines' => '989898',
			),
			'labels' => array(),
			'data' => array(),
			'tooltip' => 'Summary :: <b>%d%%</b> of highest<br/><b>%d</b> for this range<br/><b>%s</b> from last range'
		);

		public function __construct($settings = array()) {
			$className = 'Html';
			if (is_array($settings) && isset($settings[0])) {
				$className = $settings[0];
			}

			elseif (is_string($settings)) {
				$className = $settings;
			}

			$engineName = $className;
			list($plugin, $className) = pluginSplit($className);

			$this->__engineName = $className . 'ChartEngine';
			$this->helpers[] = $engineName . 'ChartEngine';
			parent::__construct();
		}

		/**
		 * draw a chart
		 *
		 * @param mixed $type the type of chart
		 * @param array $data the data for the chart
		 * @param string $engine the engine to use
		 *
		 * @return string the chart.
		 */
		public function draw($type = '', $data = array(), $engine = null){
			if(!$type){
				trigger_error(__('Please specify the chart type', true), E_USER_WARNING);
				return false;
			}
			
			$engine = (string)$engine;
			$this->__engineName = !empty($engine) ? $engine : $this->__engineName;
			if(!$this->__engineName){
				trigger_error(__('You need to specify the engine to use', true), E_USER_WARNING);
				return false;
			}

			if(!empty($data)){
				$this->__buildChartData($type, $data);
			}

			return $this->__dispatch();
		}

		/**
		 * set the type of chart. $type is the method that will be called in the
		 * selected engine. if an array is passed the extra details will be sent
		 * to data['config'] where the engine will have access to it.
		 *
		 * @param mixed $type string or array
		 * @return ChartsHelper method chaning
		 */
		public function setType($type = null){
			if(is_string($type) && !empty($type)){
				$this->data['type'] = $type;
				return $this;
			}
			else if(is_array($type) && !empty($type)){
				$this->data['type'] = current(array_keys($type));
				$this->data['config'] = current($type);
			}
			else{
				$this->data['type'] = 'nothing';
				trigger_error(__('Could not detect the type of chart', true), E_USER_NOTICE);
			}
			
			return $this;
		}

		/**
		 * set the title of the chart
		 *
		 * @param string $title a title for the chart
		 * @access public
		 *
		 * @return ChartsHelper method chaning
		 */
		public function setTitle($title = null){
			// nothing set and somthing from draw()
			if((!isset($this->data['title']) || empty($this->data['title'])) && isset($this->__originalData['title'])){
				$this->data['title'] = $this->__originalData['title'];
			}
			
			// something was passed.
			if($title){
				$this->data['title'] = $title;
			}

			// still nothing, just set it to false
			if(!isset($this->data['title']) || !is_string($this->data['title']) || empty($this->data['title'])){
				$this->data['title'] = false;
			}

			return $this;
		}

		/**
		 * set the charts width
		 *
		 * @param int $width the width of the chart
		 * @access public
		 *
		 * @return ChartsHelper method chaning
		 */
		public function setWidth($width){
			$this->data['width'] = $width;
			if(!is_int($this->data['width']) || (int)$this->data['width'] < 1){
				$this->data['width'] = $this->__defaults['width'];
				trigger_error(sprintf(__('Width (%s) is not an int or too small, using default', true), $width), E_USER_NOTICE);
			}

			return $this;
		}

		/**
		 * set the charts height
		 *
		 * @param int $height the height of the chart
		 * @access public
		 *
		 * @return ChartsHelper method chaning
		 */
		public function setHeight($height){
			$this->data['height'] = $height;
			if(!is_int($this->data['height']) || (int)$this->data['height'] < 1){
				$this->data['height'] = $this->__defaults['height'];
				trigger_error(sprintf(__('Height (%s) is not an int or too small, using default', true), $height), E_USER_NOTICE);
			}

			return $this;
		}

		/**
		 * set the charts size, can take an array of width / height or just width,
		 * an int for width or a comma seperated list of width,height
		 *
		 * @param int $height the height of the chart
		 * @access public
		 *
		 * @return ChartsHelper method chaning
		 */
		public function setSize($size = null){
			if(!$size && isset($this->__originalData['size'])){
				$size = $this->__originalData['size'];
			}
			if(!$size && isset($this->__originalData['width'])){
				$size = $this->__originalData['width'];
				if(isset($this->__originalData['height'])){
					$size .= ',' . $this->__originalData['height'];
				}
			}
			if(!$size){
				trigger_error(__('Size could not be determined, using default', true), E_USER_NOTICE);
				$size = $this->__defaults['width'] . ',' . $this->__defaults['height'];
			}
			
			if(!is_array($size)){
				$size = explode(',', $size);
			}

			$count = count($size);			
			switch($count){
				case 1:
					$this->setWidth((int)trim($size[0]));
					break;
				
				case 2:
					$this->setWidth((int)trim($size[0]));
					$this->setHeight((int)trim($size[1]));
					break;
				
				default:
					trigger_error(sprintf(__('Size should be an array of either one or two values, you passed %s', true), $count), E_USER_NOTICE);
					break;
			}

			return $this;
		}

		public function setAxes($axes = null){
			if(!$axes && isset($this->__originalData['axes'])){
				$axes = $this->__originalData['axes'];
			}
			
			$this->data['axes'] = array_keys($axes);

			$this->setLabels($axes);
			
			return $this;
		}

		public function setLabels($data){
			if(!isset($this->data['axes'])){
				trigger_error(__('Axes should be set before labels, skipping', true), E_USER_NOTICE);
				return $this;
			}
			if(!isset($this->data['data'])){
				trigger_error(__('Data should be set before labels, skipping', true), E_USER_NOTICE);
				return $this;
			}

			foreach((array)$this->data['axes'] as $axes){				
				$this->data['labels'][$axes] = $this->__anythingToArray($axes, $data[$axes], ',', true);
				if(empty($this->data['labels'][$axes])){
					$this->data['labels'][$axes] = $this->__defaultLablesFromData($this->data['data']);
				}
			}

			return $this;
		}

		public function setData($data = null){
			if(!$data){
				$data = $this->data['data'];
			}

			$this->data['data'] = !$this->normalize
				? $data
				: $this->__normalizeData($data);

			unset($data);
			
			return $this;
		}

		/**
		 * set color options for the chart
		 *
		 * @param array $colors key values like background -> ff0000
		 * @access public
		 *
		 * @return ChartsHelper method chaning
		 */
		public function setColors($colors = null){
			if(!$colors){
				$colors = $this->__originalData['color'];
			}
			
			if(!is_array($colors) || empty($colors)){
				$this->data['color'] = $this->__defaults['color'];
			}
			else{
				$this->data['color'] = array_merge(
					$this->__defaults['color'],
					$colors
				);
			}

			return $this;
		}

		/**
		 * set the scale and increments for the graph.
		 * 
		 * @param array $data the data for the chart
		 * @param int $increments the number of steps in the axis
		 * @access public
		 *
		 * @return ChartsHelper method chaning
		 */
		public function setScale($data, $increments = null){
			// could be nested data sets
			// get min or 0
			// get max
			$this->data['scale'] = array(
				'min' => 0,
				'max' => 100,
				'increments' => (int)$increments > 0 ? $increments : 6
			);

			return $this;
		}

		public function setSpacing($spacing = null){
			if(!$spacing){
				$spacing = $this->__originalData['spacing'];
			}

			$this->data['spacing'] = array_merge(
				array(
					'padding' => 0,
					'width' => 0,
				),
				$spacing
			);

			return $this;
		}

		public function setTooltip($tooltip = null){
			if(!$tooltip || !is_string($tooltip)){
				$tooltip = $this->__originalData['tooltip'];
			}

			if($tooltip === true){
				$this->data['tooltip'] = $this->__defaults['tooltip'];
			}
			else{
				$this->data['tooltip'] = $tooltip;
			}

			return $this;
		}

		private function __buildChartData($type, $data){
			$this->__originalData = $data;

			if(isset($data['normalize'])){
				$this->normalize = (bool)$data['normalize'];
			}

			$this
				->validateData()
				->setData()
				->setType($type)
				->setTitle()
				->setSize()
				->setAxes()
				->setColors()
				->setSpacing()
				->setTooltip()
				;
		}

		private function validateData($data = null){
			if(!$data){
				$data = $this->__originalData['data'];
			}
			
			if(!isset($data[0][0])){
				if(!is_array($data)){
					$data = array($data);
				}

				$data = array($data);
			}

			foreach($data as $k => $v){
				$this->data['data'][$k] = $this->__anythingToArray('data', $v, ',', true);
			}

			unset($data);
			
			$this->__getStats();

			return $this;
		}

		/**
		 * convert large values to % values so that the data being manipulated
		 * is much smaller. There is no difference in the presentation
		 * 
		 * @param array $data the data for the chart to be normalized
		 * @param int $max used internally, do not pass things in here.
		 */
		private function __normalizeData($data, $max = null){
			if(!$this->normalize){
				$this->data['ratio'] = 'fixed';
				return $this;
			}

			$this->data['ratio'] = 'percentage';

			foreach($data as $k => $_data){
				foreach($_data as $kk => $__data){
					$data[$k][$kk] = round(($__data / $this->data['values']['max']) * 100);
				}
			}

			return $data;
		}

		private function __anythingToArray($field, $data, $delimiter = ',', $return = false){
			if(!$data && isset($this->__originalData[$field])){
				$data = $this->__originalData[$field];
			}

			if(!is_array($data) && !empty($data)){
				$data = explode($delimiter, $data);
			}

			if(!$data || empty($data)){
				if($return){
					return false;
				}
				
				$this->data[$field] = false;
			}
			else{
				if($return){
					return $data;
				}

				$this->data[$field] = $data;
				unset($data);
			}

			return isset($this->data[$field]);
		}

		private function __defaultLablesFromData($data){
			$max = $this->__getMaxDataValue($data);
			$min = $this->__getMinDataValue($data);
			$average = $this->__getAverageDataValue($data);
			
			return range($min, $max, round(($max - $min) / 6));
		}

		private function __getStats(){
			$this->__getMaxDataValue();
			$this->__getMinDataValue();
			$this->__getAverageDataValue();
		}

		private function __getMaxDataValue($data = null){
			if(!$data){
				$data = $this->data['data'];
			}

			if(!isset($this->data['values']['max'])){
				$this->data['values']['max'] = max(Set::flatten($data));
			}

			unset($data);
			return $this->data['values']['max'];
		}

		private function __getMinDataValue($data = null){
			if(!$data){
				$data = $this->data['data'];
			}

			if(!isset($this->data['values']['min'])){
				$this->data['values']['min'] = min(Set::flatten($data));
			}

			unset($data);
			return $this->data['values']['min'];
		}

		/**
		 * get the average amount for all the data that was passed for chart rendering
		 *
		 * @param <type> $data
		 * @access public
		 *
		 * @return int the average
		 */
		private function __getAverageDataValue($data = null){
			if(!$data){
				$data = $this->data['data'];
			}

			if(!isset($this->data['values']['average'])){
				$flat = Set::flatten($data);
				$this->data['values']['average'] = round(array_sum($flat) / count($flat));
			}
			
			unset($data, $flat);			
			return $this->data['values']['average'];
		}

		/**
		 * do some final checks and then if all is good trigger the chart engine
		 * that is needed and return the chart.
		 *
		 * @return string some html or what ever the chart engine sends back
		 */
		private function __dispatch(){
			if(empty($this->data)){
				trigger_error(__('You need to pass data, or use the methods to set data', true), E_USER_WARNING);
				return false;
			}

			if(!is_callable(array($this->{$this->__engineName}, $this->data['type']))){
				trigger_error(sprintf('(%s) does not have a (%s) chart type', get_class($this->{$this->__engineName}), $this->data['type']), E_USER_WARNING);
				return false;
			}

			return $this->{$this->__engineName}->{$this->data['type']}($this->data);
		}
	}

	/**
	 * Base class for chart engines.
	 *
	 * This just defines a few of the more common types that would be used, they
	 * will just throw errors if used and the selected engine does not support the
	 * chosen type.
	 */
	class ChartsBaseEngineHelper extends AppHelper{
		/**
		 * An area chart or area graph displays graphically quantitive data. It
		 * is based on the line chart. The area between axis and line are commonly
		 * emphasized with colors, textures and hatchings. Commonly one compares
		 * with an area chart two or more quantities.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function area($data){
			trigger_error(sprintf(__('%s does not have area() implemented', true), get_class($this)), E_USER_WARNING);
		}
		
		/**
		 * A bar chart or bar graph is a chart with rectangular bars with lengths
		 * proportional to the values that they represent. The bars can also be
		 * plotted horizontally. Bar charts are used for plotting discrete (or
		 * 'discontinuous') data i.e. data which has discrete values and is not
		 * continuous.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function bar($data){
			trigger_error(sprintf(__('%s does not have bar() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * Box charts, also called box plots or box and whisker charts, are a type
		 * of chart that shows the grouping of one or more series into quartiles
		 * (quartiles are groups that span 25% of the range of values, with the
		 * possible exception of outliers).
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function box($data){
			trigger_error(sprintf(__('%s does not have box() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * A candlestick chart is a style of bar-chart used primarily to describe
		 * price movements of a security, derivative, or currency over time. It
		 * is a combination of a line-chart and a bar-chart, in that each bar
		 * represents the range of price movement over a given time interval.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function candlestick($data){
			trigger_error(sprintf(__('%s does not have candlestick() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * 
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function gauge($data){
			trigger_error(sprintf(__('%s does not have gauge() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * A line chart or line graph is a type of graph, which displays
		 * information as a series of data points connected by straight line
		 * segments. It is a basic type of chart common in many fields. It is an
		 * extension of a scatter graph, and is created by connecting a series
		 * of points that represent individual measurements with line segments.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function line($data){
			trigger_error(sprintf(__('%s does not have line() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * An organizational chart (often called organization chart, org chart,
		 * organigram(me), or organogram(me)) is a diagram that shows the structure
		 * of an organization and the relationships and relative ranks of its
		 * parts and positions/jobs. The term is also used for similar diagrams,
		 * for example ones showing the different elements of a field of knowledge
		 * or a group of languages.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function organization($data){
			trigger_error(sprintf(__('%s does not have organization() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * A pie chart (or a circle graph) is a circular chart divided into sectors,
		 * illustrating proportion. In a pie chart, the arc length of each sector
		 * (and consequently its central angle and area), is proportional to the
		 * quantity it represents.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function pie($data){
			trigger_error(sprintf(__('%s does not have pie() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * A radar chart is a graphical method of displaying multivariate data
		 * in the form of a two-dimensional chart of three or more quantitative
		 * variables represented on axes starting from the same point. The relative
		 * position and angle of the axes is typically uninformative. The radar
		 * chart is also known as web chart, spider chart, star chart, cobweb
		 * chart, star plot, irregular polygon, polar chart, or kiviat diagram.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function radar($data){
			trigger_error(sprintf(__('%s does not have radar() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * A scatter plot or scattergraph is a type of mathematical diagram using
		 * Cartesian coordinates to display values for two variables for a set of
		 * data. The data is displayed as a collection of points, each having the
		 * value of one variable determining the position on the horizontal axis
		 * and the value of the other variable determining the position on the.
		 * vertical axis.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function scatter($data){
			trigger_error(sprintf(__('%s does not have scatter() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * Convert data into a table
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function table($data){
			trigger_error(sprintf(__('%s does not have table() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * Treemaps display hierarchical (tree-structured) data as a set of nested
		 * rectangles. Each branch of the tree is given a rectangle, which is
		 * then tiled with smaller rectangles representing sub-branches. A leaf
		 * node's rectangle has an area proportional to a specified dimension on
		 * the data.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function treemap($data){
			trigger_error(sprintf(__('%s does not have treemap() implemented', true), get_class($this)), E_USER_WARNING);
		}

		/**
		 * Venn diagrams or set diagrams are diagrams that show all hypothetically
		 * possible logical relations between a finite collection of sets
		 * (aggregation of things). They are used to teach elementary set theory,
		 * as well as illustrate simple set relationships in probability, logic,
		 * statistics, linguistics and computer science.
		 *
		 * @param array $data
		 * @access public
		 *
		 * @return string
		 */
		public function venn($data){
			trigger_error(sprintf(__('%s does not have venn() implemented', true), get_class($this)), E_USER_WARNING);
		}
	}