<?php
	class ShortUrl extends ShortUrlsAppModel{
		public $viewable = true;

		/**
		 * direct delete or to trash
		 *
		 * @access public
		 * @var bool
		 */
		public $noTrash = true;

		/**
		 * skip confirmation
		 *
		 * @access public
		 * @var bool
		 */
		public $noConfirm = true;

		/**
		 * A string of chars used in the encoding / decoding
		 * 
		 * @var string
		 */
		private $__codeSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		/**
		 * The length of the $__codeSet
		 * 
		 * @var int
		 */
		private $__base;


		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);
			
			$this->validate = array(
				'url' => array(
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('That url has already been shortened', true)
					),
					'url' => array(
						'rule' => 'someTypeOfUrl',
						'message' => __('Please enter a valid url (something that does in href)', true)
					),
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the url to shorten', true)
					)
				)
			);
			
			$this->__base = strlen($this->__codeSet);
		}

		/**
		 * make sure we save only valid urls.
		 *
		 * @param array the field being validated
		 * @return bool if it is valid or not
		 */
		public function someTypeOfUrl($field){
			// absolute url
			if(substr(current($field), 0, 1) == '/'){
				return true;
			}
			
			// full url
			else if(preg_match('/^((mailto\:|(news|(ht|f)tp(s?))\:\/\/){1}\S+).*$/', current($field))){
				return true;
			}

			else if(false){
				// validate json so that you can use a cake url that can change with routing.
			}
			
			return false;
		}

		/**
		 * Convert a long url to a short one.
		 *
		 * @param string $url the url to shroten
		 * @return string the code of the short url to be used with slugUrl event
		 */
		public function shorten($url = null){
			if(!$url){
				return false;
			}

			$data['ShortUrl']['url'] = $url;
			$this->create();
			if(!$this->save($data)){
				if(current($this->validationErrors) == $this->validate['url']['isUnique']['message']){
					$id = $this->find(
						'first',
						array(
							'conditions' => array(
								'ShortUrl.url' => $url
							)
						)
					);

					$this->id = isset($id['ShortUrl']['id']) ?$id['ShortUrl']['id'] : false;
					
					if(!$this->id){
						return false;
					}
				}
			}

			return $this->__encode($this->id);
		}

		/**
		 * convert a short url to a long one.
		 * 
		 * @param string $code the code from the short url
		 * @return mixed false on fail, string url on true
		 */
		public function getUrl($code = null){
			if(!$code){
				return false;
			}

			$check = $this->read(null, $this->__decode($code));

			if(!empty($check)){
				return $check['ShortUrl']['url'];
			}

			return false;
		}

		/**
		 * encode the id to a few chars
		 *
		 * @param int $id
		 * @return string the code for the short url
		 */
		private function __encode($id){
			$return = '';

			while ($id > 0) {
				$return = substr($this->__codeSet, ($id % $this->__base), 1) . $return;
				$id = floor($id / $this->__base);
			}

			return $return;
		}

		/**
		 * decode a short url to figure out the correct long url that will be
		 * returned.
		 *
		 * @param string $data the code from the short url
		 * @return int the id of the record that has the url
		 */
		private function __decode($data){
			$return = '';
			$i = strlen($data);

			for ($i; $i; $i--) {
				$return += strpos($this->__codeSet, substr($data, (-1 * ($i - strlen($data))), 1)) * pow($this->__base,$i-1);
			}

			return $return;
		}
	}