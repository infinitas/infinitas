<?php
	App::import('Helper', 'Google.GoogleAppHelper');
	/**
	 * http://code.google.com/apis/maps/documentation/staticmaps/#Usage
	 * @author dogmatic69
	 *
	 * @todo custom icons
	 * @todo
	 */
	class StaticMapHelper extends GoogleAppHelper{
		/**
		 * Set some defaults
		 * @var array
		 */
		protected $_default = array(
			'location' => array(),
			'sensor' => 'false', // true, false or both
			'zoom' => 10,
			'visible' => null,
			'size' => array(
				'width' => 640,
				'height' => 480
			),
			'image_type' => 'png32', // png, png8, png32, jpg
			'map_type' => 'roadmap', // roadmap, satellite, hybrid, terrain
			'markers' => array( //nested array
				array(
					'size' => '', // tiny, mid, small or normal
					'color' => '', // like css with no #
					'label' => '', // {A-Z, 0-9}
					'location' => array(
						// like above
					)
				)
			)
		);

		public function draw($data = array(), $imageProperties = array(), $linkOnly = false){
			if(!$linkOnly){
				$cache = $this->_checkCache(array($data, $imageProperties));
				if($cache !== false){
					return $cache;
				}
			}

			$this->query = $data;

			if(!isset($this->query['location']) || !$this->__getValidLocation($this->query['location'])){				
				if(!isset($this->query['markers']) || count($this->query['markers']) < 2){
					$this->errors[] = 'Invalid location';
					return false;
				}
			}
			
			if(isset($this->query['visible']) && empty($this->query['visable'])){
				$this->query['visible'] = $this->__getValidLocation($this->query['visible']);
			}
			else{
				$this->query['visible'] = null;
			}

			if(!$this->query['visible'] && !(isset($this->query['zoom']) || !$this->__validZoom($this->query['zoom']))){
				$this->query['zoom'] = $this->_default['zoom'];
			}

			if(!isset($this->query['size']) || !$this->__validSize($this->query['size'])){
				$this->errors[] = 'Invalid size';
				return false;
			}

			if(!isset($this->query['image_type']) || !$this->__validImageType($this->query['image_type'])){
				$this->query['image_type'] = $this->_default['image_type'];
			}

			if(!isset($this->query['map_type']) || !$this->__validMapType($this->query['map_type'])){
				$this->query['map_type'] = $this->_default['map_type'];
			}

			$this->query['sensor'] = $this->__SensorType(isset($this->query['sensor']) ? $this->query['sensor'] : false);
			$this->query['markers'] = $this->__markersValid(isset($this->query['markers']) ? $this->query['markers'] : array());

			$url = $this->__buildQuery();
			if($linkOnly){
				return $url;
			}
			
			$this->_writeCache(array($data, $imageProperties), $url);
			return $this->Html->image(
				$url,
				$imageProperties
			);

		}

		private function __buildQuery(){
			if($this->query['visible']){
				$return[] = 'visible='.$this->query['visible'];
			}
			else{
				$return[] = 'center='.implode(',', (array)$this->query['location']);
				$return[] = 'zoom='.$this->query['zoom'];
			}
			$return[] = 'size='.$this->query['size']['width'].'x'.$this->query['size']['height'];
			$return[] = 'sensor='.$this->query['sensor'];

			foreach($this->query['markers'] as $marker){
				$return[] = $this->__formatMarker($marker);
			}

			$return = $this->__url.'?'.implode('&', array_filter($return));

			return $return;
		}

		/**
		 * points on the map
		 *
		 * The set of marker style descriptors is a series of value assignments
		 * separated by the pipe (|) character. This style descriptor defines the
		 * visual attributes to use when displaying the markers within this marker
		 * descriptor.
		 *
		 * @param unknown_type $marker
		 */
		private function __formatMarker($marker){
			if(empty($marker['location'])){
				return null;
			}
			$return = array();
			$return[] = $marker['location'];

			if(!empty($marker['color'])){
				$return[] = 'color:'.$marker['color'];
			}

			if(!empty($marker['label'])){
				$return[] = 'label:'.$marker['label'];
			}

			if(!empty($marker['size'])){
				$return[] = 'size:'.$marker['size'];
			}

			return 'markers='.implode('|', $return);
		}

		/**
		 * There is no need to modyfy this class.
		 * @author dogmatic69
		 */
		/**
		 * The map api url
		 * @var string
		 */
		protected $__url = 'http://maps.google.com/maps/api/staticmap';

		/**
		 * Max height allowed
		 * @var int
		 */
		protected $__maxHeight = 640;

		/**
		 * max width allowed
		 * @var int
		 */
		protected $__maxWidth = 640;

		/**
		 * Allowed image types
		 * @var array
		 */
		protected $__imageTypes = array(
			'png',
			'png8',
			'png32',
			'gif',
			'jpg',
			'jpg-baseline'
		);

		/**
		 * Allowed map types
		 * @var array
		 */
		protected $__mapTypes = array(
			'roadmap',
			'satellite',
			'terrain',
			'hybrid'
		);

		protected $__markerSizes = array(
			'normal',
			'tiny',
			'small',
			'med'
		);

		/**
		 * by address could return wrong maps.
		 *
		 * @param array $data
		 */
		protected function __getValidLocation($location){
			if(isset($location) && !empty($location) && !is_array($location)){
				return urlencode($location);
			}

			if(isset($location['latitude']) && isset($location['longitude'])){
				$valid = $this->__validLatitude($location['latitude']) && $this->__validLongitude($location['longitude']);
				if($valid){
					return urlencode(implode(',', $location));
				}
			}

			return null;
		}

		/**
		 * valid latitude.
		 *
		 * Latitudes can take any value between -90 and 90 while
		 *
		 * @param float $latitude
		 */
		protected function __validLatitude($latitude){
			return -90 <= (float)$latitude && (float)$latitude <= 90;
		}

		/**
		 * valid longitude.
		 *
		 * longitude values can take any value between -180 and 180.
		 *
		 * @param float $longitude
		 */
		protected function __validLongitude($longitude){
			return -180 <= (float)$longitude && (float)$longitude <= 180;
		}

		/**
		 * valid zoom
		 *
		 * Zoom levels between 0 (the lowest zoom level, in which the entire
		 * world can be seen on one map) to 21+ (down to individual buildings)
		 *
		 * NOTE: not all zoom levels appear at all locations on the earth
		 *
		 * @param unknown_type $zoom
		 */
		protected function __validZoom($zoom){
			return 0 <= $zoom && $zoom <= 21;
		}

		/**
		 * valid image size
		 *
		 * Images may be retrieved in sizes up to 640 by 640 pixels.
		 * @param array $sizes
		 */
		protected function __validSize($sizes){
			return $sizes['width'] <= $this->__maxWidth && $sizes['height'] <= $this->__maxHeight;
		}

		/**
		 * valid image format
		 *
		 * Images may be returned in several common web graphics formats: GIF, JPEG and PNG.
		 *
		 * @param string $imageType
		 */
		protected function __validImageType($imageType){
			return in_array($imageType, $this->__imageTypes);
		}

		/**
		 * valid map type
		 *
		 * roadmap (default) specifies a standard roadmap image, as is normally shown on the Google Maps website. If no maptype value is specified, the Static Maps API serves roadmap tiles by default.
    	 * satellite specifies a satellite image.
    	 * terrain specifies a physical relief map image, showing terrain and vegetation.
   	 	 * hybrid specifies a hybrid of the satellite and roadmap image, showing a transparent layer of major streets and place names on the satellite image.
   	 	 *
		 * @param string $mapType
		 */
		protected function __validMapType($mapType){
			return in_array($mapType, $this->__mapTypes);
		}

		/**
		 * maps for gps device
		 *
		 * specifies whether the application requesting the static map is using a sensor to determine the user's location.
   	 	 *
		 * @param string $mapType
		 */
		protected function __SensorType($sensor){
			switch(true){
				case $sensor === true:
				case strtolower($sensor) === 'true':
					return 'true';
					break;

				default:
					return 'false';
					break;
			}
		}

		/**
		 * validate and remove unneded params for markers
		 * @param array $markers
		 */
		protected function __markersValid($markers){
			if(empty($markers) || !is_array($markers)){
				return array();
			}

			$return = array();
			$i = 0;
			foreach($markers as $marker){
				$return[$i]['size'] =  $this->__validMarkerSize(isset($marker['size']) ? $marker['size'] : '');
				$return[$i]['color'] = $this->__validMarkerColor(isset($marker['color']) ? $marker['color'] : '');
				$return[$i]['label'] = $this->__validMarkerLabel(isset($marker['label']) ? $marker['label'] : '');
				$return[$i]['location'] = $this->__getValidLocation(isset($marker['location']) ? $marker['location'] : '');
				++$i;
			}

			unset($markers);
			return $return;
		}

		/**
		 * check the size param
		 *
		 * if its the default dont add it, waste of url chars :)
		 * @param unknown_type $size
		 */
		protected function __validMarkerSize($size){
			if(empty($size) || !in_array(strtolower($size), $this->__markerSizes) && strtolower($size) !== $this->__markerSizes[0]){
				return null;
			}
			return $size;
		}

		/**
		 * check colors are valid.
		 * @param unknown_type $color
		 */
		protected function __validMarkerColor($color){
			if(empty($color) || !preg_match('/^([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?$/', 'fff111')){
				return null;
			}

			return '0x'.$color;
		}

		/**
		 * a single uppercase alphanumeric character from the set {A-Z, 0-9}
		 * @param $label
		 */
		protected function __validMarkerLabel($label){
			if(!empty($label) && preg_match('/^[A-Z0-9]$/', '1')){
				return $label;
			}
			return '';
		}
	}
