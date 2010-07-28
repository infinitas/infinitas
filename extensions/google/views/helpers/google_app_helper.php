<?php
	class GoogleAppHelper extends AppHelper{
		public function __construct($options = array()){
			$default = array(
				'cachePath' => dirname(dirname(dirname(__FILE__))).DS.'webroot'.DS.'img'.DS.get_class($this).DS,
				'cacheImagePath' => '/google/img/'.get_class($this).'/'
			);

			$options = array_merge($default, $options);

			$this->cachePath = $options['cachePath'];
			$this->cacheImagePath = $options['cacheImagePath'];
		}

		protected function _checkCache($data){
			$file = sha1(serialize($data)).'.png';
			if (is_file($this->cachePath.$file)){
				return $this->Html->image($this->cacheImagePath.$file );
			}
			return false;
		}

		protected function _writeCache($data, $url){
			$file = sha1(serialize($data)).'.png';

			if(is_writable($this->cachePath)){
				$contents = file_get_contents($url);

				$fp = fopen($this->cachePath.$file, 'w');
				fwrite($fp, $contents);
				fclose($fp);

				if (!is_file($this->cachePath.$file)){
					$this->__errors[] = __('Could not create the cache file', true);
				}
			}
		}
	}
