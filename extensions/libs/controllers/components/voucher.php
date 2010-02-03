<?php
	class VoucherComponent extends Object {
		/**
		 * components being used here
		 */
		var $components = array();

		/**
		* The path to the voucher template
		*/
		var $path = '';
		var $voucher = 'gift-voucher.png';
		var $font = 'C:\xampp\php\extras\fonts\ttf\Vera.ttf';

		var $errors = null;

		var $voucherSize = array();

		var $voucherRecource = null;

		/**
		 * Controllers initialize function.
		 */
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);
			$this->path = APP.'extensions'.DS.'libs'.DS.'webroot'.DS.'img'.DS;
			$this->voucher = $this->path.$this->voucher;
		}

		function getVoucher(){
			if (!is_file($this->font)) {
				$this->errors[] = 'Font file missing';
				return false;
			}

			if ($this->__generateNewVoucher()) {

				$this->__writeVoucherCode();
				$this->__writeUserName();
				$this->__writeExpiryDate();

				$this->__saveVoucher();
			}

			pr( $this->errors );
			exit;
		}

		/**
		 * Creates a blank voucher for manipulation.
		 *
		 * @return bool true if image is created false if not
		 */
		function __generateNewVoucher(){
			// get the size of the image
			$this->voucherSize     = getimagesize($this->voucher);

			// create a new blank image
			$this->output          = imagecreatetruecolor($this->voucherSize[0], $this->voucherSize[1]);

			// load the voucher template
			$this->voucherRecource = imagecreatefrompng($this->voucher);

			// copy the voucher to the png
			if (imagecopyresampled($this->output, $this->voucherRecource, 0, 0, 0, 0, $this->voucherSize[0], $this->voucherSize[1], $this->voucherSize[0], $this->voucherSize[1])) {
				return true;
			}

			$this->errors[] = 'Could not create the new voucher';
		}

		/**
		 * Write the code to the voucher
		 *
		 * @return bool true if text added false if not
		 */
		function __writeVoucherCode($color = array(255, 255, 0)){
			$yellow = imagecolorallocate($this->output, $color[0], $color[1], $color[2]);
			$voucherUuidCode = md5(time());

			if (imagestring($this->output, 3, 500, 310, $voucherUuidCode, $yellow)) {
				return true;
			}

			$this->errors[] = 'Could not write the voucher code';
			return false;
		}

		/**
		 * Write the users name
		 *
		 * @return bool true if text added false if not
		 */
		function __writeUserName($userName = null, $color = array(255, 255, 0), $shadow = true){
			if (!$userName) {
				$this->error[] = 'No User name passed. Using session name';
				$userName = $this->Controller->Session->read('Auth.User.username');

				if (!$userName) {
					$this->errors[] = 'No User in session';
					return false;
				}
			}

			$color = imagecolorallocate($this->output, $color[0], $color[1], $color[2]);

			// Add some shadow to the text
			if ($shadow) {
				imagettftext($this->output, 20, 0, 22, 35, imagecolorallocate($this->output, 128, 128, 128), $this->font, $userName);
			}

			//imagestring($this->output, 100, 20, 17, $userName, $color)
			if (imagettftext($this->output, 20, 0, 20, 33, $color, $this->font, $userName)) {
				return true;
			}

			$this->errors[] = 'Could not write the voucher code';
			return false;
		}

		/**
		 * Write the expiry date.
		 *
		 * Will default to a date 1 week from now if there is no date passed.
		 *
		 * @return bool true if text added false if not
		 */
		function __writeExpiryDate($date = null, $color = array(255, 255, 0)){
			if ($date && $date < date('Y-m-d H:i:s')) {
				$this->__voidVoucher();
			}

			if (!$date) {
				$this->errors[] = 'No date passed. Using 1 week from now';
				$date = date('D, j \o\f F Y', mktime(0,0,0,date('m'),date('d')+7,date('Y')));
			}

			imagettftext($this->output, 10, 0, 480, 32, imagecolorallocate($this->output, 0, 0, 0), $this->font, __('Expires:', true));

			//imagestring($this->output, 100, 20, 17, $userName, $color)
			if (imagettftext($this->output, 10, 0, 550, 32, imagecolorallocate($this->output, $color[0], $color[1], $color[2]), $this->font, $date)) {
				return true;
			}

			$this->errors[] = 'Could not write the voucher code';
			return false;
		}

		/**
		 * Void the voucher
		 *
		 * This can be called to void the voucher.  Will print big voids all over.
		 *
		 * @param string $text the void text to display
		 * @param array $color the rgb color of the text
		 * @return true if text is added, false if not.
		 */
		function __voidVoucher($text = 'Expired', $color = array(128, 128, 128)){
			$color = imagecolorallocate($this->output, $color[0], $color[1], $color[2]);
			$text = __($text, true);

			$return = imagettftext($this->output, 60, 45, 100, 300, $color, $this->font, $text) &&
			imagettftext($this->output, 60, 45, 300, 300, $color, $this->font, $text) &&
			imagettftext($this->output, 60, 45, 500, 300, $color, $this->font, $text);

			if (!$return) {
				$this->errors[] = 'Error adding some void text';
			}

			return $return;
		}

		/**
		 * Gets the data from the resource.
		 *
		 * from here it can be saved to file or output for download.
		 *
		 * @return mixed $return the raw image data.
		 */
		function __getImageData(){
			ob_start();
				imagepng($this->output);
			$return = ob_get_contents();
			ob_end_clean();

			return $return;
		}

		/**
		 * Save to file
		 *
		 * Saves the image to disk.
		 *
		 * @return bool true if file writes, false if not
		 */
		function __saveVoucher(){
			$newVoucher = $this->path.'test.png';

			App::import('File');
			$this->File = new File($newVoucher, true);

			$this->File->open('w');
			if ($this->File->write($this->__getImageData())) {
				return true;
			}

			$this->errors[] = 'Could not write the file';
			return false;
		}
	}
?>