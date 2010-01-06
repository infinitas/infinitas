<?php
/**
* Google Charts Helper class file.
*
* Simplifies creating charts with the google charts api.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://www.dogmatic.co.za
* @package google
* @subpackage google.views.helpers.chart
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*/
class ChartHelper extends AppHelper {
	var $helpers = array('Html', 'Session', 'Number');

	/**
	* Country codes by continent.
	*
	* This is used by the chart helper to generate maps.
	*
	* @var array
	* @access public
	*/
	var $country_to_continent = array(
		'Africa' => array(
			'KM', 'GW', 'KE', 'NA', 'LS', 'LR', 'LY', 'MG', 'MW', 'ML',
			'GN', 'GH', 'CG', 'CI', 'DJ', 'EG', 'GQ', 'ER', 'ET', 'GA',
			'GM', 'MR', 'MU', 'YT', 'SO', 'ZA', 'SD', 'SZ', 'TZ', 'TG',
			'TN', 'UG', 'EH', 'SL', 'SC', 'MA', 'MZ', 'NE', 'NG', 'RE',
			'RW', 'SH', 'ST', 'SN', 'ZM', 'BF', 'BI', 'BJ', 'AO', 'CM',
			'CV', 'ZW', 'DZ', 'BW', 'TD', 'CF'
			),
		'Antarctica' => array(
			'GS', 'HM', 'AQ', 'TF', 'BV'
			),
		'Asia' => array(
			'IO', 'AM', 'ID', 'SG', 'KP', 'MM', 'JO', 'JP', 'IN', 'IL',
			'BD', 'PK', 'PH', 'NP', 'BN', 'MN', 'LB', 'HK', 'GE', 'TL',
			'KR', 'CY', 'UZ', 'VN', 'MO', 'TH', 'MY', 'LA', 'KH', 'CC',
			'TW', 'KG', 'LK', 'CX', 'MV', 'CN', 'BT'
			),
		'Europe' => array(
			'MK', 'MC', 'AL', 'BE', 'MT', 'MD', 'ME', 'BY', 'ES', 'SJ',
			'SE', 'CH', 'AD', 'FO', 'TR', 'UA', 'GB', 'SI', 'SK', 'NL',
			'NO', 'PL', 'PT', 'RO', 'RU', 'AT', 'SM', 'RS', 'AX', 'LU',
			'LT', 'FI', 'IT', 'IM', 'IE', 'FR', 'HR', 'GR', 'IS', 'VA',
			'HU', 'GI', 'DE', 'BG', 'LV', 'JE', 'BA', 'EE', 'GG', 'LI',
			'DK', 'CZ'
			),
		'ME' => array(
			'PS', 'OM', 'QA', 'AZ', 'AE', 'TM', 'SA', 'AF', 'KZ', 'SY',
			'TJ', 'YE', 'IQ', 'IR', 'KW', 'BH'
			),
		'North America' => array(
			'GD', 'GP', 'KN', 'DM', 'PM', 'VC', 'AG', 'DO', 'AW', 'AI',
			'SV', 'GL', 'CA', 'TT', 'KY', 'BS', 'US', 'HN', 'AN', 'BB',
			'MS', 'JM', 'MX', 'BZ', 'MQ', 'BM', 'NI', 'CR', 'TC', 'LC',
			'VG', 'PA', 'GT', 'VI', 'CU', 'HT', 'PR'
			),
		'Oceania' => array(
			'WF', 'TK', 'VU', 'TV', 'CK', 'AS', 'UM', 'TO', 'NR', 'PN',
			'PG', 'PW', 'FM', 'MP', 'NF', 'NU', 'NZ', 'NC', 'GU', 'SB',
			'FJ', 'KI', 'MH', 'PF', 'AU', 'WS'
			),
		'South America' => array(
			'CL', 'CO', 'VE', 'BO', 'FK', 'UY', 'GY', 'PE', 'AR', 'BR',
			'PY', 'GF', 'SR', 'EC'
			)
		);

	/**
	* Setting up charts.
	*
	* This setup decides what values can and cant be drawn with the helper. It stops you from
	* submitting params for graphs that are not ment to be there.  If there is something that is
	* not showing in your graph check Chart::debug as it alert you if you added something that is
	* not supported.
	*
	* @var array
	* @access public
	*/
	var $setup = array(
		'pie3d' => array(
			// required
			'data' => true, 'labels' => true, 'size' => true,
			// optional
			'colors' => true,
			'fill' => array('type' => true, 'color' => true, 'angle' => true, 'offset' => true,),
			'scale' => array(0 => array('min' => true, 'max' => true)),
			'title' => array('text' => true, 'color' => true, 'size' => true),
			'legend' => array(
				'labels' => true,
				'position' => array('horizontal' => true, 'vertical' => true)
				),
			'orientation' => true
			),
		'pie2d' => array(
			// required
			'data' => true, 'labels' => true, 'size' => true,
			// optional
			'colors' => true,
			'fill' => array('type' => true, 'color' => true, 'angle' => true, 'offset' => true,),
			'scale' => array(0 => array('min' => true, 'max' => true)),
			'title' => array('text' => true, 'color' => true, 'size' => true),
			'legend' => array(
				'labels' => true,
				'position' => array('horizontal' => true, 'vertical' => true)
				),
			'orientation' => true
			),
		'bar' => array(
			// required
			'data' => array(0 => true), 'labels' => true, 'size' => true,
			// optional
			'colors' => true,
			'fill' => array('type' => true, 'color' => true, 'angle' => true, 'offset' => true,),
			'scale' => array(0 => array('min' => true, 'max' => true)),
			'title' => array('text' => true, 'color' => true, 'size' => true),
			'legend' => array(
				'labels' => true,
				'position' => array('horizontal' => true, 'vertical' => true)
				),
			'orientation' => true,
			'grid' => true,
			'marker' => array(0 => array(
					'shape' => true,
					'color' => true,
					'index' => true, // the key of the data set
					'size' => true,
					'z-index' => true
					)
				)

			),

		'line' => array(
			// required
			'data' => true, 'labels' => true, 'size' => true,
			)
		);

	/**
	* the query that is sent to the api to generate the chart.
	*
	* @var string
	* @access public
	*/
	var $return = null;

	/**
	* turn debug on or off.
	*
	* @var bool
	* @access public
	*/
	var $debug = false;

	/**
	* turn cache on or off.
	*
	* @var bool
	* @access public
	*/
	var $cache = false;

	/**
	* Do not modify these
	*/
	/**
	* the seperator between params in the url.
	*
	* @var string
	* @access public
	*/
	var $paramSeperator = '&';

	/**
	* the max size of the graph (height x width).
	*
	* @var int
	* @access private
	*/
	var $__maxSize = 300000;

	/**
	* the api address.
	*
	* @var string
	* @access private
	*/
	var $__apiUrl = 'http://chart.apis.google.com/chart?';

	/**
	* array of errors.
	*
	* holds a list of errors / warnings that were generated while trying
	* generate the query string for the api
	*
	* @var array
	* @access private
	*/
	var $__errors = array();

	/**
	* internal to hold origional data for caching.
	*/
	var $input = array();

	/**
	* Map names to code.
	*
	* this is used to conver the english names used in the helper to
	* the codes needed by google to create the graph.
	*
	* @var array
	* @access public
	*/
	var $map = array(
		'data' => array(// done
			'code' => 'chd=t:',
			'seperator' => ','
			),
		'labels' => array(// done
			'code' => 'chl=',
			'seperator' => '|'
			),
		'size' => array(// done
			'code' => 'chs=',
			'seperator' => 'x'
			),
		'colors' => array(
			'code' => '',
			'seperator' => ''
			),
		'solid_fill' => array(// done
			'code' => 'chf=',
			'format' => '%s,%s,%s', // type,style,color
			'seperator' => '|'
			),
		'gradient_file' => array(// gradient and lines ... offset == width
			'code' => 'chf=',
			'format' => '%s,%s,%d,%s,%f', // type,style,angle,color,offset
			'seperator' => '|'
			),
		'special_fill' => array(
			'code' => '',
			'format' => ''
			),
		'scale' => array(
			'code' => '',
			'seperator' => ''
			),
		'title' => array(// done
			'code' => 'chtt=',
			'seperator' => '+'
			),
		'title_color' => array(// done
			'code' => 'chts=',
			'seperator' => ','
			),
		'legend' => array(
			'code' => '',
			'seperator' => ''
			),
		'orientation' => array(
			'code' => 'chp='
			)
		);

	/**
	* Map from names to codes.
	*
	* This is used to generat the maps based on a friendly name.
	*
	* @var array
	* @access public
	*/
	var $chartTypes = array(
		// pie charts
		'pie2d' => 'cht=p',
		'pie3d' => 'cht=p3',
		'concentric' => 'cht=pc',
		// bar charts
		'bar' => array(
			'horizontal' => 'cht=bhs',
			'vertical' => 'cht=bvs',
			'horizontal_grouped' => 'cht=bhg',
			'vertical_grouped' => 'cht=bvg'
			),
		// line charts
		'line' => 'cht=lc',
		'spark' => 'cht=ls',
		'compare' => 'cht=lxy',
		// radar
		'radar' => 'cht=r',
		// 'radar_fill' => 'cht=rs',
		// other
		'scatter' => 'cht=s',
		'venn' => 'cht=v',
		// special
		'meter' => 'cht=gom',
		'map' => 'cht=t',
		'qr_code' => 'cht=qr'
		);

	public function test($name = 'pie3d') {
		switch($name) {
			case 'pie3d':
				return '<img border="0" alt="Yellow pie chart" src="http://chart.apis.google.com/chart?chs=250x100&chd=t:60,40&cht=p3&chl=Hello|World"/>';
				break;

			case 'pie2d':
				return '<img border="0" alt="Yellow pie chart" src="http://chart.apis.google.com/chart?chs=250x100&chd=t:60,40&cht=p&chl=Hello|World"/>';
				break;

			case 'bar':
				return '<img border="0" alt="Yellow pie chart" src="http://chart.apis.google.com/chart?cht=bhs&chs=200x125&chd=s:ello&chco=4d89f9"/>';
				break;

			default: ;
		} // switch
	}

	function display($name = null, $data = array()) {
		// used for cache
		$this->originalData = array('name' => $name, 'data' => $data);

		if (!$name) {
			$this->__errors[] = __('Please specify what graph you need', true);
			return false;
		}

		if (empty($data)) {
			$this->__errors[] = __('No data was given', true);
			return false;
		}

		if ($this->cache && $this->__checkCache()) {
			return $this->__checkCache();
		}

		$this->__reset();

		$this->__setChartType($name);

		foreach($data as $key => $value) {
			if (is_array($name)) {
				if (!isset($this->setup[$name['name']][$key])) {
					$this->__errors = __('Param "' . $key . '" is not supported in chart type "' . $name . '"', true);
					continue;
				}
			}else if (!isset($this->setup[$name][$key])) {
				$this->__errors = __('Param "' . $key . '" is not supported in chart type "' . $name . '"', true);
				continue;
			}

			switch($key) {
				case 'data':
				case 'labels':
					$this->__setData($key, $value);
					break;

				case 'orientation':
					$this->__setOrientaion($value);
					break;

				case 'size':
					$this->__setSize($value);
					break;

				case 'title':
					$this->__setTitle($value);
					break;

				case 'fill':
					$this->__setFill($key, $value);
					break;
			} // switch
		}

		return $this->__render($data);
	}

	function __checkCache() {
		$path = APP . 'plugins' . DS . 'google' . DS . 'webroot' . DS . 'img' . DS . 'chart_cache' . DS;
		$file = sha1($this->originalData['name'] . serialize($this->originalData['data'])) . '.jpg';

		if (is_file($path . $file)) {
			return $this->Html->image('/google/img/chart_cache/' . $file);
		}

		$this->__errors[] = 'File <b>' . $path . $file . '</b> does not exist';
	}

	function __writeCache($url) {
		$path = APP . 'plugins' . DS . 'google' . DS . 'webroot' . DS . 'img' . DS . 'chart_cache' . DS;
		$file = sha1($this->originalData['name'] . serialize($this->originalData['data'])) . '.jpg';

		$contents = file_get_contents($url);

		$fp = fopen($path . $file, 'w');
		fwrite($fp, $contents);
		fclose($fp);

		if (!is_file($path . $file)) {
			$this->__errors[] = __('Could not create the cache file', true);
		}
	}

	function __reset() {
		$this->output = null;
		$this->__errors = null;
		$this->__debug = null;
		$this->return = null;
	}

	function __setFill($key, $data) {
		if (!isset($data[0])) {
			$data = array($data);
		}

		foreach($data as $k => $fill) {
			switch($fill['position']) {
				case 'background':
					$param[$k][] = 'bg';
					break;

				case 'chart':
					$param[$k][] = 'c';
					break;

				case 'transparency':
					$param[$k][] = 'a';
					break;
			} // switch
			switch($fill['type']) {
				case 'solid':
					$param[$k][] = 's';
					break;
			} // switch
			if (!isset($fill['color'])) {
				unset($param[$k]);
			}else {
				$param[$k][] = $fill['color'];
			}

			if (isset($param[$k])) {
				if (count($param[$k] == 3)) {
					$key = 'solid_fill';
					$param[$k] = array_values($param[$k]);
					$param[$k] = sprintf($this->map['solid_fill']['format'], $param[$k][0], $param[$k][1], $param[$k][2]);
				}
			}
		}

		$this->__setReturn($key, $param);
	}

	function __setOrientaion($value) {
		$this->__setData('orientation', round((((float)$value * pi()) / 180), 3));
	}

	function __setTitle($title) {
		if (is_array($title) && isset($title['text'])) {
			$params = array();
			if (isset($title['color'])) {
				$params[] = $title['color'];
			}

			if (isset($title['size'])) {
				if (empty($params)) {
					$params[] = '4F4F4F';
					$this->__errors[] = __('No color was set, adding a default', true);
				}
				$params[] = (int)$title['size'];
			}

			$title = str_replace('<br/>', '|', $title['text']);
			$this->__setData('title_color', $params);
		}else {
			$title = str_replace('<br/>', '|', $title);
		}
		$this->__setData('title', explode(' ', $title));
	}

	function __render($data) {
		$data['html'] = array();
		if (!isset($data['html']['title']) && isset($data['title'])) {
			if (!is_array($data['title'])) {
				$data['html']['title'] = $data['title'];
			}else if (is_array($data['title']['text'])) {
				$data['html']['title'] = $data['title']['text'];
			}
		}

		$this->output = $this->__apiUrl . implode($this->paramSeperator, $this->return);

		$graph = $this->Html->image($this->output,
			$data['html']
			);

		if ($this->cache) {
			$this->__writeCache($this->output);
		}

		if ($this->debug) {
			$graph .= '<div style="border:1px dotted gray;">';
			$graph .= '<h4>Query String</h4>';
			$graph .= '<p>' . $this->output . '</p>';
			if (is_array($this->__errors) && !empty($this->__errors)) {
				$graph .= '<h4>Errors</h4>';
				foreach($this->__errors as $error) {
					$graph .= '<p>' . $error . '</p>';
				}
			}
			$graph .= '</div>';
		}

		return $graph;
	}

	function __setSize($data) {
		if (!is_array($data)) {
			$data = explode(',', $data, 3);
		}

		if ($data[0] > 1000) {
			$data[0] = 1000;
			$this->erros[] = __('Width to big, reset to 1000px', true);
		}

		if ($data[1] > 1000) {
			$data[1] = 1000;
			$this->erros[] = __('Height to big, reset to 1000px', true);
		}

		if ($data[0] * $data[1] > $this->__maxSize) {
			$this->erros[] = __('Sizes exceed the maximum for google charts api', true);
			$data = array(100, 100);
		}

		return $this->__setReturn('size', $data);
	}

	function __setData($key, $data) {
		if (!is_array($data)) {
			$data = explode(',', $data);
		}

		return $this->__setReturn($key, $data);
	}

	function __setReturn($key, $data) {
		$return = $this->map[$key]['code'];

		if (isset($this->map[$key]['seperator'])) {
			$return .= implode($this->map[$key]['seperator'], $data);
		}else {
			$return .= implode('', $data);
		}

		$this->return[] = $return;
		return true;
	}

	/**
	* ChartHelper::__setChart()
	*
	* checks that the name passed in is part of the valid charts that can be drawn.
	* saves the data to the $this->return
	*
	* @param string $name
	* @retrun bool
	*/
	function __setChartType($name) {
		$nameArray = array();
		if (is_array($name)) {
			$nameArray = $name;
			if (!isset($name['name'])) {
				$this->__errors[] = __('Please specify the type of chart with array( \'name\' => \'some_name\' ); or just \'some_name\'.', true);
				return false;
			}

			if (isset($name['type'])) {
				$name = $name['name'] . '_' . $name['type'];
			}else {
				$name = $name['name'];
			}
		}
		if (!empty($nameArray)) {
			if (!in_array(str_replace($nameArray['name'] . '_', '', $name), array_flip($this->chartTypes[$nameArray['name']]))) {
				$this->__errors[] = __('Incorect chart type', true);
				return false;
			}
		}else if (!isset($this->chartTypes[$name])) {
			$this->__errors[] = __('Incorect chart type', true);
			return false;
		}

		if (!empty($nameArray)) {
			$this->return[] = $this->chartTypes[$nameArray['name']][str_replace($nameArray['name'] . '_', '', $name)];
		}else {
			$this->return[] = $this->chartTypes[$name];
		}

		return true;
	}

	function __autoColor() {
	}

	function __autoScale() {
	}

	function encode($data = array(), $type = 't') {
		if (!is_array($data)) {
			$data = array($data);
		}
	}

	/**
	* legacy code below
	*/
	var $settings = array(
		'api_address' => 'http://chart.apis.google.com/chart?',
		'size' => array(
			'width' => 300,
			'height' => 300,
			'name' => 'chs='
			),
		'charts' => array(
			'meter' => array(
				'name' => 'cht=gom',
				'data' => 'chd=t:',
				'label' => 'chl='
				),
			'sparkline' => array(
				'name' => 'cht=ls',
				'color' => 'chco=',
				'data' => 'chd=t:',
				'labels' => array(
					'axis' => array(
						'name' => 'chxt=',
						'where' => array('x', 'y')
						),
					'label' => array(
						'name' => 'chxl=',
						'data' => array()
						),
					),
				),
			'pie' => array(
				'name' => 'cht=p3',
				'data' => 'chd=t:',
				'label' => 'chl='
				),
			'map' => array(
				'type' => array(
					'name' => 'chtm=',
					'type' => 'world'
					),
				'colors' => array(
					'name' => 'chco=',
					'seperator' => ','
					),
				'places' => array(
					'name' => 'chld=',
					'seperator' => ''
					),
				'data' => array(
					'name' => 'chd=t:',
					'seperator' => ','
					),
				'size' => array(
					'name' => 'chs='
					)
				)
			)
		);

	function map($type = 'world', $size = 'large', $data = null) {
		$chart = $this->settings['charts']['map'];

		if (!$data) {
			return false;
		}
		if (!$type) {
			$type = $chart['type']['type'];
		}

		switch($size) {
			case 'small':
				$width = 220;
				$height = 110;
				break;

			case'medium' :
				$width = 330;
				$height = 165;
				break;

			case'large' :
				$width = 440;
				$height = 220;
				break;
		} // switch
		$size = $width . 'x' . $height;

		$render = $this->settings['api_address'] . 'cht=t&' .
		$this->settings['size']['name'] . $size . '&amp;' .
		$chart['data']['name'] . implode($chart['data']['seperator'], $data['amount']) . '&amp;' .
		$chart['colors']['name'] . implode($chart['colors']['seperator'], $data['colors']) . '&amp;' .
		$chart['places']['name'] . implode($chart['places']['seperator'], $data['countries']) . '&amp;' .
		$chart['type']['name'] . $type . '&amp;' .
		'chf=bg,s,EAF7FE';

		return $this->Html->image($render,
			array(
				'title' => $chart['type']['type'],
				'alt' => $chart['type']['type']
				)
			);
	}

	function pie3D($data = array(), $labels = array(), $size = array()) {
		$chart = $this->settings['charts']['pie'];

		if (empty($data)) {
			return false;
		}

		if (empty($size)) {
			$size = $this->settings['size'];
		}

		if ($check = $this->checkSize($size) != true) {
			return $check;
		}

		$render = $this->settings['api_address'] .
		$this->settings['size']['name'] .
		$size['width'] . 'x' . $size['height'] . '&amp;' .
		$chart['name'] . '&amp;' .
		$chart['data'] . implode(',', $data) . '&amp;' .
		$chart['label'] . implode('|', $labels);

		return $this->Html->image($render,
			array(
				'title' => implode(' vs. ', $labels),
				'alt' => implode(' vs. ', $labels),
				'width' => $size['width'],
				'height' => $size['height']
				)
			);
	}

	function sparkline($data = array(), $axis_label = array(), $size = array()) {
		$chart = $this->settings['charts']['sparkline'];

		if (empty($data)) {
			return false;
		}

		if (empty($size)) {
			$size = $this->settings['size'];
		}

		if ($check = $this->checkSize($size) != true) {
			return $check;
		}

		$max = 0;
		foreach($data as $k => $v) {
			$data[$k] = (int)$v + 0;
			$bottom_label[] = $k;

			if ($v > $max) {
				$max = $v;
			}
		}
		$i = 0;
		while($i <= $max) {
			$x_inc[$i] = $i;
			$i++;
		} // while
		$render = $this->settings['api_address'] .
		$this->settings['size']['name'] .
		$size['width'] . 'x' . $size['height'] . '&amp;' .
		$chart['name'] . '&amp;' .
		$chart['data'] . implode(',', $data) . '&amp;' .
		$chart['labels']['axis']['name'] . implode(',', $chart['labels']['axis']['where']) . '&amp;' .
		$chart['labels']['label']['name'] .
		'0:|' . implode('|', $bottom_label) . '|' .
		'1:|' . implode('|', $x_inc) .
		'&amp;chds=0,' . $max;

		return $this->Html->image($render,
			array(
				'title' => '',
				'alt' => '',
				'width' => $size['width'],
				'height' => $size['height']
				)
			);
	}

	function meter($data = null, $label = '', $size = array()) {
		$chart = $this->settings['charts']['meter'];

		if ($data == null || is_array($data)) {
			return false;
		}

		if (empty($size)) {
			$size = $this->settings['size'];
		}

		$size['height'] = $size['width'] / 2;

		if ($check = $this->checkSize($size) != true) {
			return $check;
		}

		if ($data <= 0) {
			$data = 1;
		}elseif ($data >= 100) {
			$data = 100;
		}

		$render = $this->settings['api_address'] .
		$this->settings['size']['name'] .
		$size['width'] . 'x' . $size['height'] . '&amp;' .
		$chart['name'] . '&amp;' .
		$chart['data'] . $this->Number->precision($data, 0) . '&amp;' .
		$chart['label'] . $label;

		return $this->Html->image($render,
			array(
				'title' => __('Health: ', true) . $this->Number->toPercentage($data, 0),
				'alt' => __('Health: ', true) . $this->Number->toPercentage($data, 0),
				'width' => $size['width'],
				'height' => $size['height']
				)
			);
	}

	function checkSize($size = array()) {
		if (empty($size)) {
			return false;
		}

		$total = $size['width'] * $size['height'];

		if ($total >= 300000) {
			return false;
		}

		return true;
	}
}

?>