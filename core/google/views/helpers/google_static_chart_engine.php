<?php
	class GoogleStaticChartEngineHelper extends GoogleChartDataFormatter{
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

		/**
		 * @brief google bar chart
		 *
		 * @copydetails ChartsBaseEngineHelper::bar()
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/googleometer_chart.html
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
	}

	class GoogleChartDataFormatter extends ChartsBaseEngineHelper{
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
		protected $_apiUrl = 'http://chart.apis.google.com/';

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
			'_global' => array(
				'size',
				'color',
				'labels',
				'data',
			),
			'bar' => array(
				'spacing',
				'scale'
			),
			'bar_horizontal' => array(
				'_indicator' => 'cht=bhs'
			),
			'bar_horizontal_group' => array(
				'_indicator' => 'cht=bhg'
			),
			'bar_vertical' => array(
				'_indicator' => 'cht=bvs'
			),
			'bar_vertical_group' => array(
				'_indicator' => 'cht=bvg'
			),
			'bar_vertical_overlay' => array(
				'_indicator' => 'cht=bvo'
			),
			'gauge' => array(
				'_indicator' => 'cht=gom',
			),
			'line' => array(
				'_indicator' => 'cht=lc',
				'scale'
			),
			'line_spark' => array(
				'_indicator' => 'cht=ls',
				'scale'
			),
			'line_xy' => array(
				'_indicator' => 'cht=ls',
				'scale'
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
			'gauge' => array(
				'series'
			),
			'bar' => array(
				'series'
			),
			'line' => array(
				'series'
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
			)
		);

		private $__sizeLimit = 300000;

		public function  __construct() {
			parent::__construct();

			foreach($this->_chartTypes as $chartType => $data){
				if($chartType == '_global'){
					continue;
				}

				if(substr($chartType, 0, 3) == 'bar'){
					$this->_chartTypes[$chartType] = array_merge(
						$this->_chartTypes['bar'],
						$this->_chartTypes[$chartType]
					);

					$this->_colorTypes[$chartType] = $this->_chartTypes['bar'];
				}

				$this->_chartTypes[$chartType] = array_merge(
					$this->_chartTypes['_global'],
					$this->_chartTypes[$chartType]
				);
			}
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
		protected function _buildChart($data){
			$this->_query = array();

			if(isset($data['extra']['scale'])){
				if($data['extra']['scale'] == 'relative'){
					$data['scale'] = array(0, $data['values']['max']);
				}
			}
			
			if(isset($data['config']['type'])){
				$this->_chartType .= '_' . strtolower($data['config']['type']);
			}
			
			if(!isset($this->_chartTypes[$this->_chartType])){
				return false;
			}

			foreach($data as $key => $value){
				if(!in_array($key, $this->_chartTypes[$this->_chartType])){
					continue;
				}
				
				$this->_query[] = $this->_formatQueryParts($key, $value);
			}
			
			array_unshift($this->_query, $this->_chartTypes[$this->_chartType]['_indicator']);

			$this->_query = $this->_apiUrl . $this->_formats['_global']['key'] . implode(
				$this->_formats['_global']['separator'],
				Set::flatten($this->_query)
			);

			if(strlen($this->_query) > 2048){
				trigger_error(sprintf(__('The query string is too long (%d chars)', true), strlen($this->_query)), E_USER_ERROR);
			}

			if(isset($data['extra']['return']) && $data['extra']['return'] = 'url'){
				return $this->_query;
			}

			return $this->_image($this->_query, $data['extra']);
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
		protected function _image($query, $extra){
			return $this->Html->image($query);
		}

		/**
		 * @brief convert the array data to a fragment to build up the query
		 *
		 * @access protected
		 */
		protected function _formatQueryParts($key, $value){
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
					$method = '_format' . ucfirst(strtolower($key));
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
		protected function _formatData($value){
			if(!is_array($value)){
				$value = array($value);
			}

			$return = array();
			foreach($value as $_value){
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
		protected function _formatLabels($value){			
			$i = 0;
			foreach($value as $k => $v){
				$_part = $i . ':' . $this->_formats['labels']['separator'];
				$return[] = $_part .implode($this->_formats['labels']['separator'], $v);
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
		protected function _formatColor($value){
			$return = array();
			foreach($value as $k => $v){
				if(!in_array($k, $this->_colorTypes[$this->_chartType])){
					continue;
				}
				if(!is_array($v)){
					$v = array($v);
				}
				$return[] = $this->_colorFormats[$k]['key'] . implode($this->_colorFormats[$k]['separator'], $v);
			}

			return $return;
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
		protected function _formatSpacing($value){
			$return = array();

			$value['type'] = isset($value['type'])
				? $value['type']
				: 'absolute';
			
			$return[] = $value['type'] == 'absolute' ? 'a' : 'r';

			// dont need the a/r as there is a number
			if(isset($value['width'])){
				$return = array($value['width']);
			}

			if(isset($value['padding'])){
				$return[] = $value['padding'];
				
				// if its a group add some padding to the group too
				if(strstr($this->_chartType, 'group')){
					$return[] = $value['padding'];
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
		protected function _formatSize($value){
			if(count($value) == 1){
				$value[] = current($value);
			}
			if(count($value) >= 2){
				$_value = $value;
				sort($_value);
				
				if($_value[0] * $_value[1] > $this->__sizeLimit){
					return false;
				}
			}

			return $this->_formatGeneric('size', $value);
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
		protected function _formatGeneric($key, $value){
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
		protected function _implode($dataType, $value){
			return implode($this->_formats[$dataType]['separator'], $value);
		}
	}