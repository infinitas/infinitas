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
		 * @brief bar
		 *
		 * @copydetails ChartsBaseEngineHelper::bar()
		 *
		 * @link http://code.google.com/apis/chart/docs/gallery/googleometer_chart.html
		 */
		public function bar($data){
			$this->_chartType = 'bar';

			return $this->_buildChart($data);
		}

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

			$this->_query = $this->_formats['_global']['key'] . implode(
				$this->_formats['_global']['separator'],
				Set::flatten($this->_query)
			);

			if(isset($data['extra']['return']) && $data['extra']['return'] = 'url'){
				return $this->_apiUrl . $this->_query;
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
			return $this->Html->image($this->_apiUrl . $query);
		}

		/**
		 * @brief convert the array data to a fragment to build up the query
		 */
		protected function _formatQueryParts($key, $value){
			if(empty($value)){
				return false;
			}
			
			switch($key){				
				case 'size':
				case 'scale':
					return $this->_formatGeneric($key, $value);
					break;

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

		protected function _formatData($value){
			if(count($value) == 1 && isset($value[0])){
				return $this->_formatGeneric('data', $value[0]);
			}

			pr($value);
			exit;
		}

		/**
		 * @brief generate the query string for lables and axes
		 *
		 * some examples 
		 * @li array(array(Groovy),array(slow,faster,crazy)) becomes chxl=0:|Groovy|1:|slow|faster|crazy
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
		 * @li data chd=t:20,40,60
		 * @li size chs=200x125
		 */
		protected function _formatGeneric($key, $value){
			return $this->_formats[$key]['key'] . implode($this->_formats[$key]['separator'], $value);
		}

		protected function _formatSize(){

		}
	}