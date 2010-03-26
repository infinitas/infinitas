<?php
	/**
	 *
	 *
	 */
	class AppError extends ErrorHandler {
		/**
		 * Constructor
		 * @access protected
		 */
		function eventError($params){
			$this->controller->set('params', $params);
			$this->_outputMessage('event_error');
		}
	}
?>