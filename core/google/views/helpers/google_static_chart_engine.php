<?php
	class GoogleStaticChartEngineHelper extends ChartsBaseEngineHelper{
		/**
		 * @brief google-o-meter
		 *
		 * @copydetails ChartsBaseEngineHelper::gauge()
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/googleometer_chart.html
		 */
		public function gauge($data){
			$this->_chartType = 'gauge';

			return $this->_buildChart($data);
		}

		public function pie($data){
			$this->_chartType = 'pie';

			return $this->_buildChart($data);
		}

		/**
		 * @brief google bar chart
		 *
		 * @copydetails ChartsBaseEngineHelper::bar()
		 *
		 * @link http://code.google.com/apis/chart/image/docs/gallery/bar_charts.html
		 */
		public function bar($data){
			$this->_chartType = 'bar';

			return $this->_buildChart($data);
		}

		/**
		 * @brief google line chart
		 *
		 * @copydetails ChartsBaseEngineHelper::line()
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/line_charts.html
		 */
		public function line($data){
			$this->_chartType = 'line';

			return $this->_buildChart($data);
		}
		
		/**
		 * @brief an array of helpers that will be used within this class
		 */
		public $helpers = array(
			'Html'
		);

		/**
		 * @brief the type of chart being rendered
		 *
		 * @property _chartType
		 * @access protected
		 */
		protected $_chartType;

		/**
		 * @brief the api url for the charts interface
		 *
		 * @property _apiUrl
		 * @access protected
		 */
		protected $_apiUrl = 'http://%d.chart.apis.google.com/';

		protected $_apiUrlIndex = 0;

		/**
		 * @brief a list of charts and valid params that can be passed
		 *
		 * This is a list of the different chart types with the possible values
		 * that can be passed to them.
		 *
		 * @property _chartTypes
		 * @access protected
		 */
		protected $_chartTypes = array(
			'bar' => array(
				'_indicator' => 'cht=bhs',
				'legend',
				'size',
				'color',
				'labels',
				'data',
				'spacing',
				'scale'
			),
			'bar_horizontal' => array(
				'_indicator' => 'cht=bhs',
				'legend',
				'size',
				'color',
				'labels',
				'data',
				'spacing',
				'scale'
			),
			'bar_horizontal_group' => array(
				'_indicator' => 'cht=bhg',
				'legend',
				'size',
				'color',
				'labels',
				'data',
				'spacing',
				'scale'
			),
			'bar_vertical' => array(
				'_indicator' => 'cht=bvs',
				'legend',
				'size',
				'color',
				'labels',
				'data',
				'spacing',
				'scale'
			),
			'bar_vertical_group' => array(
				'_indicator' => 'cht=bvg',
				'legend',
				'size',
				'color',
				'labels',
				'data',
				'spacing',
				'scale'
			),
			'bar_vertical_overlay' => array(
				'_indicator' => 'cht=bvo',
				'legend',
				'size',
				'color',
				'labels',
				'data',
				'spacing',
				'scale'
			),
			'gauge' => array(
				'_indicator' => 'cht=gom',
				'legend',
				'line_style',
				'size',
				'color',
				'labels',
				'data',
			),
			'line' => array(
				'_indicator' => 'cht=lc',
				'scale',
				'legend',
				'size',
				'color',
				'labels',
				'data',
			),
			'line_spark' => array(
				'_indicator' => 'cht=ls',
				'legend',
				'scale',
				'size',
				'color',
				'labels',
				'data',
			),
			'line_xy' => array(
				'_indicator' => 'cht=lxy',
				'legend',
				'scale',
				'size',
				'color',
				'labels',
				'data',
				'line_marker'
			)
		);

		/**
		 * @brief the map of data types to the format.
		 *
		 * This is a map of the parts to build up the urls. Every data type
		 * has a key and a seperator. This is used to implode the arrays into
		 * the correct format.
		 *
		 * @property _formats
		 * @access protected
		 */
		protected $_formats = array(
			'_global' => array(
				'key' => 'chart?',
				'separator' => '&'
			),
			'axes' => array(
				'key' => 'chxt=',
				'separator' => ','
			),
			'data' => array(
				'key' => 'chd=t:',
				'separator' => ','
			),
			'labels' => array(
				'key' => 'chxl=',
				'separator' => '|'
			),
			'size' => array(
				'key' => 'chs=',
				'separator' => 'x'
			),
			'scale' => array(
				'key' => 'chds=',
				'separator' => ','
			),
			'spacing' => array(
				'key' => 'chbh=',
				'separator' => ','
			),
			'legend' => array(
				'key' => 'chdl=',  // the labels
				'separator' => '|'
			),
			'legend_position' => array(
				'key' => 'chdlp=',
				'separator' => '|'
			),
			'line_style' => array(
				'key' => 'chls=',
				'separator' => '|' // items ,
			),
			'line_marker' => array(
				'key' => 'chm=',
				'separator' => ','
			)
		);

		/**
		 * @brief map of chart to allowed colors
		 *
		 * This is a list of the charts with the possible color values that can
		 * be passed to them. Some charts are able to specify background colors
		 * and others are not. This sorts the differences out
		 *
		 * @property _colorTypes
		 * @access protected
		 */
		protected $_colorTypes = array(
			'bar' => array(
				'series',
				'fill'
			),
			'bar_horizontal' => array(
				'series',
				'fill'
			),
			'bar_horizontal_group' => array(
				'series',
				'fill'
			),
			'bar_vertical' => array(
				'series',
				'fill'
			),
			'bar_vertical_group' => array(
				'series',
				'fill'
			),
			'bar_vertical_overlay' => array(
				'series',
				'fill'
			),
			'gauge' => array(
				'series',
				'fill'
			),
			'line' => array(
				'series',
				'fill'
			),
			'line_spark' => array(
				'series',
				'fill'
			),
			'line_xy' => array(
				'series',
				'fill'
			)
		);

		protected $_fillTypes = array(
			'bar' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'bar_horizontal' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'bar_horizontal_group' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'bar_vertical' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'bar_vertical_group' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'bar_vertical_overlay' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'gauge' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'line' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'line_spark' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			),
			'line_xy' => array(
				'solid' => 's',
				'gradient' => 'lg',
				'striped' => 'ls'
			)
		);

		/**
		 * @brief map color types to the structures
		 *
		 * The colors are similar to the different $_formats so this holds
		 * the map to the color types and the data used to build that part
		 * of the query string.
		 *
		 * @property _colorFormats
		 * @access protected
		 */
		protected $_colorFormats = array(
			'series' => array(
				'key' => 'chco=',
				'separator' => ','
			),
			'fill' => array(
				'key' => 'chf=',
				'separator' => ','
			)
		);

		/**
		 * @brief the size limit for the chart
		 *
		 * @link
		 */
		private $__sizeLimit = 300000;

		public function  __construct() {
			parent::__construct();
		}

		/**
		 * @brief main method for building a chart
		 *
		 * This is where it all starts, the keys are checked against the
		 * chart type being rendered and if its a valid option for the
		 * type of chart requested the data is passed along to be formatted
		 * into the different pieces of the query string.
		 *
		 * @param array $data one of the value arrays from the ChartsHelper data
		 * @access protected
		 *
		 * @return string the chart requested
		 */
		public function _buildChart($data){
			$this->_query = array();

			if(isset($data['extra']['scale'])){
				if($data['extra']['scale'] == 'relative'){
					$data['scale'] = array(0, $data['values']['max']);
				}
			}

			if(isset($data['config']['type'])){
				$this->_chartType .= '_' . strtolower($data['config']['type']);
			}

			if(!isset($this->_chartTypes[(string)$this->_chartType])){
				trigger_error(sprintf(__('The chart type "%s" is invalid', true), (string)$this->_chartType), E_USER_ERROR);
				return false;
			}

			if($this->_chartType == 'gauge' && isset($data['extra']['arrows'])){
				$data['line_style'] = $data['extra']['arrows'];
			}
			if(strstr($this->_chartType, 'line')){
				if(isset($data['extra']['line_style'])){
					$data['line_style'] = $data['extra']['line_style'];
				}

				if(isset($data['extra']['line_marker'])){
					$data['line_marker'] = $data['extra']['line_marker'];
				}
			}

			foreach($data as $key => $value){
				if(!in_array($key, $this->_chartTypes[$this->_chartType])){
					continue;
				}

				$this->_query[] = $this->_formatQueryParts($key, $value);

			}

			$url = $this->_generateFullUrl();

			$data['extra']['return'] = isset($data['extra']['return']) ? $data['extra']['return'] : 'image';

			switch($data['extra']['return']){
				case 'url':
					return $url;
					break;

				case 'image':
					return $this->_image($url, $data['extra']);
					break;
			}
		}

		/**
		 * @brief generate the final full url for rendering
		 *
		 * @return string the full url and query string
		 */
		public function _generateFullUrl(){
			array_unshift($this->_query, $this->_chartTypes[$this->_chartType]['_indicator']);

			$return = $this->_apiUrl() . $this->_formatGeneric('_global', Set::flatten($this->_query));

			if(strlen($return) > 2048){
				trigger_error(sprintf(__('The query string is too long (%d chars)', true), strlen($return)), E_USER_ERROR);
			}
			
			$this->_query = null;

			return $return;
		}

		/**
		 * @brief generate different urls for downloading multi images at a time
		 *
		 * @link http://code.google.com/apis/chart/docs/making_charts.html#enhancements
		 * 
		 * @return string the domain to pull images from
		 */
		public function _apiUrl(){
			if($this->_apiUrlIndex > 9){
				$this->_apiUrlIndex = 0;
			}
			
			return sprintf($this->_apiUrl, $this->_apiUrlIndex++);
		}

		/**
		 * @brief generate the required markup for displaying as an image
		 *
		 * @param string $query the query that was generated
		 * @param array|string|integer $extra any extra data that was passed from the ChartsHelper
		 * @access protected
		 *
		 * @return string some html markup
		 */
		public function _image($query, $extra){
			$extra = isset($extra['image']) ? $extra['image'] : array();
			return $this->Html->image($query, $extra);
		}

		/**
		 * @brief convert the array data to a fragment to build up the query
		 *
		 * @access protected
		 */
		public function _formatQueryParts($key, $value){
			if(empty($value)){
				return false;
			}

			switch($key){
				case 'scale':
					return $this->_formatGeneric($key, $value);
					break;

				case 'size':
				case 'data':
				case 'labels':
				case 'color':
				case 'spacing':
				case 'legend':
				case 'line_style':
				case 'line_marker':
					$method = '_format' . Inflector::camelize($key);
					return call_user_func_array(array($this, $method), array($value));
					break;

				default:
					var_dump($key);
					pr($value);
					exit;
					break;
			}
		}

		/**
		 * @brief format the data array into the query
		 *
		 * @param array $value the data array to be formatted
		 * @access protected
		 *
		 * @return string a piece of the query string
		 */
		public function _formatData($value){
			if(!is_array($value[0])){
				$value = array($value);
			}

			$return = array();
			foreach($value as $_value){
				if(is_array(current($_value))) {
					foreach($_value as $__value) {
						$return[] = $this->_implode('data', $__value);
					}
					continue;
				}
				$return[] = $this->_implode('data', $_value);
			}
			
			return $this->_formats['data']['key'] . implode('|', $return);
		}

		/**
		 * @brief generate the query string for lables and axes
		 *
		 * some examples
		 * @li array(array(Groovy),array(slow,faster,crazy)) becomes chxl=0:|Groovy|1:|slow|faster|crazy
		 *
		 * @access protected
		 */
		public function _formatLabels($value){
			$i = 0;
			$return = array();
			foreach($value as $k => $v){
				$_part = $i . ':' . $this->_formats['labels']['separator'];
				$return[] = $_part . implode($this->_formats['labels']['separator'], $v);
				++$i;
			}

			return array(
				'labels' => $this->_formatGeneric('labels', $return),
				'axes' => $this->_formatGeneric('axes', array_keys($value))
			);
		}

		/**
		 * @li labels chxl=0:|Groovy|1:|slow|faster|crazy
		 *
		 * @access protected
		 */
		public function _formatColor($value){
			$return = array();
			foreach($value as $k => $v){
				if(!in_array($k, $this->_colorTypes[$this->_chartType])){
					continue;
				}
				
				if(!is_array($v)){
					$v = array($v);
				}

				if(empty($v)){
					continue;
				}


				if($k == 'fill') {
					if(strstr($this->_chartType, 'bar')) {
						$__data = array();
						foreach($v as $dataSet => $fillData) {
							$fillData['type'] = isset($fillData['type']) ? $fillData['type'] : 'solid';
							$tmp = array('b' . $dataSet, $this->_fillTypes[$this->_chartType][$fillData['type']], $fillData['color']);
							
							$__data[] = implode($this->_colorFormats[$k]['separator'], $tmp);
						}

						$return[] = $this->_colorFormats[$k]['key'] . implode('|', $__data);
						continue;
					}
				}

				if(isset($v[0]) && is_array($v[0])){
					$colors = array();
					foreach($v as $kk => $vv){
						$colors[] = implode('|', $vv);
					}

					$v = $colors;
				}
				

				$return[] = $this->_colorFormats[$k]['key'] . implode($this->_colorFormats[$k]['separator'], $v);
			}

			return $return;
		}

		/**
		 * @brief custom fill colors
		 *
		 * @todo this needs to be implemented
		 *
		 * @link http://code.google.com/apis/chart/docs/chart_params.html#gcharts_gradient_fills
		 * @link http://code.google.com/apis/chart/docs/chart_params.html#gcharts_striped_fills
		 * @link http://code.google.com/apis/chart/docs/chart_params.html#gcharts_solid_fills
		 *
		 */
		public function _formatColorFill($value){

		}

		/**
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/bar_charts.html#chbh
		 *
		 * @param array $value
		 * @access protected
		 *
		 * @return <type>
		 */
		public function _formatSpacing($value){
			$return = array();

			$value['type'] = isset($value['type']) ? (string)$value['type'] : 'absolute';
			switch($value['type']){
				case isset($value['width']) && is_int($value['width']):
					$return[] = $value['width'];
					break;
				
				case 'relative':
					$return[] = 'r';
					$value['padding'] = is_float($value['padding']) ? $value['padding'] : $value['padding'] / 100;
					break;

				case 'absolute':
					$return[] = 'a';
					break;

				default:
					trigger_error(sprintf(__('Spacing type should be absolute or relative, not %s', true), $value['type']), E_USER_WARNING);
					break;
			}

			if(isset($value['padding'])){
				$return[] = $value['padding'];

				// if its a group add some padding to the group too
				if(strstr($this->_chartType, 'group')){
					if(isset($value['grouping'])){
						$return[] = $value['grouping'];
					}
					else{
						$return[] = $value['padding'] * 2;
					}
				}
			}

			return $this->_formatGeneric('spacing', $return);
		}

		/**
		 * @brief format a size array into part of the query string
		 *
		 * If only one param is passed then the image will be square.
		 *
		 * @access protected
		 */
		public function _formatSize($value){
			if(!is_array($value)){
				$value = array('width' => $value);
			}
			
			if(!isset($value['width']) && !isset($value['height'])){
				trigger_error(__('No size specified', true), E_USER_ERROR);
				return false;
			}

			if(isset($value['width']) && !isset($value['height'])){
				$value['height'] = $value['width'];
			}
			else if(isset($value['height']) && !isset($value['width'])){
				$value['width'] = $value['height'];
			}

			if($value['width'] * $value['height'] > $this->__sizeLimit){
				trigger_error(
					sprintf(
						__('Size of %dpx is greater than maximum allowed size %spx', true),
						$value['width'] * $value['height'],
						$this->__sizeLimit
					),
					E_USER_ERROR
				);

				return false;
			}

			return $this->_formatGeneric('size', array($value['width'], $value['height']));
		}

		/**
		 * @brief generate query string for lengend
		 *
		 * 2 parts to the legend is the labels and the layout.
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/bar_charts.html#gcharts_legend
		 *
		 * @param <type> $value
		 * @return <type>
		 */
		public function _formatLegend($value){
			if(!isset($value['labels']) || empty($value['labels'])){
				trigger_error(__('Skipping legend, no lables specified', true), E_USER_WARNING);
				return false;
			}
			$value['position'] = isset($value['position']) ? $value['position'] : 'default';
			$value['order'] = isset($value['order']) ? $value['order'] : 'default';
			
			$position = array();
			switch($value['position']){
				case 'bottom_horizontal':
				case 'bottom':
					$position[] = 'b';
					break;

				case 'bottom_vertical':
					$position[] = 'bv';
					break;

				case 'top':
				case 'top_horizontal':
					$position[] = 't';
					break;

				case 'top_vertical':
					$position[] = 'tv';
					break;

				case 'left':
				case 'left_vertical':
					$position[] = 'l';
					break;

				case 'right':
				case 'right_vertical':
				case 'default':
				default:
					$position[] = 'r';
					break;
			}

			switch($value['order']){
				case is_array($value['order']):
					if(count($value['order']) != count($value['labels'])){
						trigger_error(
							sprintf(
								__('Count of orders (%d) does not match count of lables (%d). Using default order ', true),
								count($value['order']),
								count($value['labels'])
							),
							E_USER_WARNING
						);
						$position[] = 'l';
						break;
					}
					$position[] = implode(',', $value['order']);
					break;

				case 'auto':
					$position[] = 'a';
					break;

				case 'reverse':
					$position[] = 'r';
					break;

				case 'default':
				default:
					$position[] = 'l';
					break;
			}

			return array(
				'legend_labels' => $this->_formatGeneric('legend', $value['labels']),
				'legend_position' => $this->_formatGeneric('legend_position', $position)
			);
		}

		/**
		 * @brief build the query string for the line style
		 *
		 * This is a bit of a custom one for the gauge chart with the arrow size
		 *
		 * @link http://code.google.com/apis/chart/docs/chart_params.html#gcharts_line_styles
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/googleometer_chart.html#introduction
		 *
		 * @param <type> $value
		 * @return <type>
		 */
		public function _formatLineStyle($value){
			$return = $out = $arrows = array();
			foreach($value as $k => $v){
				$out[] = isset($v['thickness']) ? $v['thickness'] : null;
				if(isset($v['dash'])){
					if(!is_array($v['dash'])){
						$v['dash'] = array((int)$v['dash']);
					}
					if(count($v['dash']) == 1){
						$v['dash'][] = current($v['dash']);
					}

					$out = array_merge($out, array($v['dash'][0], $v['dash'][1]));
				}
				
				if(isset($v['arrow'])){
					$arrows[] = (int)$v['arrow'];
				}
				
				$return[] = implode(',', $out);
				$out = array();
			}

			return $this->_formatGeneric('line_style', array_merge($return, $arrows));
		}

		public function _formatLineMarker($value){
			pr($value);
		}

		/**
		 * @brief convert arrays into the parts of the query string
		 *
		 * This method does all the generic conversions of data to strings based
		 * on the data types setup in the _formats property
		 *
		 * @link http://code.google.com/apis/chart/docs/data_formats.html
		 *
		 * @li data 'data' => array(20, 40, 60) -> chd=t:20,40,60
		 * @li size 'size' => array('width' => 200, 'height' => 125)) -> chs=200x125
		 *
		 * @access protected
		 */
		public function _formatGeneric($key, $value){
			if(!is_array($value)){
				trigger_error(sprintf(__('Value for %s is type %s and expecting array', true), $key, gettype($value)), E_USER_WARNING);
				return false;
			}
			
			return $this->_formats[$key]['key'] . $this->_implode($key, $value);
		}

		/**
		 * @brief implode an array with the separator needed to form the correct query string
		 *
		 * @param string $dataType a key from _formats
		 * @param array $data the data being exploded
		 * @access protected
		 *
		 * @return string the imploded data
		 */
		public function _implode($dataType, $value){
			if(!is_array($value)){
				trigger_error(sprintf(__('Value for %s is type %s and expecting array', true), $dataType, gettype($value)), E_USER_WARNING);
				return false;
			}

			if(!isset($this->_formats[$dataType])){
				trigger_error(sprintf(__('No format available for %s', true), $dataType), E_USER_WARNING);
				return false;
			}
			
			return implode($this->_formats[$dataType]['separator'], $value);
		}

		/**
		 * @parma set the chart type
		 */
		public function setType($type = ''){
			$this->_chartType = $type;
		}
	}