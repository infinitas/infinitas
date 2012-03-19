<?php
	class GoogleAdsenseHelper extends InfinitasHelper {
		public $clientId = null;
		/**
		 * @brief default ad configuration
		 * 
		 * @var array 
		 */
		private $__defaultConfig = array(
			'type' => 'horizontal_link',
			'size' => '468x15',
			'format' => 'text'
		);
		
		/**
		 * @brief customised ad configuration
		 * 
		 * @var array 
		 */
		private $__currentConfig = array();
		
		private $__jsUrl = 'http://pagead2.googlesyndication.com/pagead/show_ads.js';
		
		/**
		 * @brief list of possible ad types
		 * 
		 * @var array 
		 */
		private $__adTypes = array(
			'vertical_link' => '',
			'horizontal_link' => ''
		);
		
		/**
		 * @brief internal count of the number of ads displayed.
		 * 
		 * As per TOS you may only have a specific number of ads, more than this
		 * could get your account closed.
		 * 
		 * http://support.google.com/adsense/bin/answer.py?hl=en&answer=48182
		 * 
		 * @var array
		 */
		private $__counter = array(
			'units' => 0,
			'links' => 0,
			'search' => 0
		);
		
		/**
		 * @brief possible display formats
		 * 
		 * @var array
		 */
		private $__adFormat = array(
			'text' => array(
				'vertical_link',
				'horizontal_link',
				'banner',
				'skyscraper',
				'square',
				'rectangle',
				'half_skyscraper',
				'half_banner',
				'half_square',
				'half_rectangle',
			),
			'image' => array(
				'banner',
				'skyscraper',
				'square',
				'rectangle'
			),
			'video' => array(
				'rectangle'
			),
			'mobile' => array(
				
			)
		);
		
		private $__adSizes = array(
			'vertical_link' => array(
				'120x90',
				'160x90',
				'180x90',
				'200x90'
			),
			'horizontal_link' => array(
				'468x15',
				'728x15'
			),
			'banner' => array(
				'728x90',
				'468x60'
			),
			'skyscraper' => array(
				'120x600',
				'160x600'
			),
			'square' => array(
				'200x200',
				'250x250'
			),
			'rectangle' => array(
				'300x250',
				'336x280'
			),
			'half_banner' => array(
				'234x60'
			),
			'half_skyscraper' => array(
				'120x240'
			),
			'half_rectangle' => array(
				'180x150'
			),
			'half_square' => array(
				'125x125'
			),
		);
		
		/**
		 * @brief load up the passed in configs
		 * 
		 * @access public
		 * 
		 * @param View $View the current View
		 * @param type $settings array of settings
		 * 
		 * @return void
		 */
		public function __construct(View $View, $settings = array()) {
			$this->__config('vertical_link', $settings);
			parent::__construct($View, $settings);
		}
		
		/**
		 * @brief configure the ads
		 * 
		 * @access public
		 * 
		 * @param mixed $config null to get current config, array to set
		 * 
		 * @return array the current config
		 */
		public function configure($config = null) {
			if($config !== null) {
				$this->__config($config);
			}
			
			return $this->__currentConfig;
		}
		
		public function display($type, $config = array()) {
			$this->__config($type, $config);
			
			if(!$this->clientId) {
				throw new Exception('Missing client ID');
			}
		}
		
		/**
		 * @brief merge the new configs with the current configs
		 * 
		 * @throws Exception 
		 * 
		 * @access private
		 * 
		 * @param array $config the config array
		 * 
		 * @return array of the new current config
		 */
		private function __config($type, $config) {
			if(!is_array($config)) {
				throw new Exception('Config should be an array');
			}
			
			$this->__currentConfig = array_merge(
				$this->__defaultConfig, 
				$this->__currentConfig, 
				$config
			);
			
			if(!empty($this->__currentConfig['clientId'])) {
				$this->clientId = $this->__currentConfig['clientId'];
			}
			
			if(!$type) {
				if(empty($this->__currentConfig['type'])) {
					throw new Exception('Ad type is required');
				}
				
				$type = $this->__currentConfig['type'];
			}
			
			$this->__setAddType($type)
				->__setFormat()
				->__setSize();
			
			return $this->__currentConfig;
			
		}
		
		/**
		 * @brief set the ad type for the current config
		 * 
		 * @throws Exception 
		 * 
		 * @access public
		 * 
		 * @param string $type the type of ad to display
		 * 
		 * @return \GoogleAdsenseHelper
		 */
		private function __setAddType($type) {
			if(!is_string($type) || !array_key_exists($type, $this->__adTypes)) {
				throw new Exception(sprintf('"%s" (%s) is not a valid add type', (string)$type, gettype($type)));
			}
			
			$this->__currentConfig['type'] = $type;
			
			return $this;
		}
		
		/**
		 * @brief set the size of the ad to be displayed
		 * 
		 * @throws Exception 
		 * 
		 * @access private
		 * 
		 * @return \GoogleAdsenseHelper
		 */
		private function __setFormat() {
			if(empty($this->__currentConfig['format'])) {
				foreach($this->__adFormat as $format => $types) {
					if(!in_array($this->__currentConfig['type'], $types)) {
						continue;
					}
					$this->__currentConfig['format'] = $format;
				}
				
				if(empty($this->__currentConfig['format'])) {
					throw new Exception(sprintf('Could not detect the format of the ad'));
				}
			}
			
			return $this;
		}
		
		/**
		 * @brief set the size of the ad to be displayed
		 * 
		 * @throws Exception 
		 * 
		 * @access private
		 * 
		 * @return \GoogleAdsenseHelper
		 */
		private function __setSize() {
			if(empty($this->__currentConfig['size'])) {
				$this->__currentConfig['size'] = current($this->__adSizes[$this->__currentConfig['type']]);
			}
			
			if(is_array($this->__currentConfig['size'])) {
				$this->__currentConfig['size'] = implode('x', $this->__currentConfig['size']);
			}
			
			if(!array_key_exists($this->__currentConfig['size'], $this->__adSizes[$this->__currentConfig['type']])) {
				throw new Exception('Selected size "" is not valid for ad type ""', $this->__currentConfig['size'], $this->__currentConfig['type']);
			}
			
			return $this;
		}
	}