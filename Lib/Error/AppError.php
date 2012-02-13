<?php
	/**
	 * @page AppError AppError
	 *
	 * @section app_error-overview What is it
	 *
	 * The error handler for Infinitas
	 *
	 * @section app_controller-usage How to use it
	 *
	 * All error are pushed through this class
	 *
	 * @section app_controller-see-also Also see
	 * @link http://api.cakephp.org/class/error-handler
	 */

	/**
	 * @brief App Error class
	 *
	 * Over load cakes default error handeling.
	 * 
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class AppError extends ErrorHandler {
		/**
		 * @brief Event errors
		 * 
		 * Create errors for events that are not configured
		 * properly.
		 *
		 * @see AppEvents
		 * @see EventCore
		 * 
		 * @param array $params the array of params to display to the user.
		 */
		public function eventError($params){
			$this->controller->set('params', $params);
			$this->_outputMessage('event_error');
		}

		public function pluginDisabledError($params){
			extract($params, EXTR_OVERWRITE);
			$this->error(array(
				'code' => $code,
				'name' => 'Disabled Plugin',
				'message' => sprintf(__("The plugin (%s) requested has been disabled, you can enable it in the backend"), $plugin)
			));
			$this->_stop(1);
		}

		/**
		 * @brief overload to show a stack trace and log some data
		 *
		 * This shows all the error pages like 404 missing files and classes. It
		 * is overloaded to add a bit of logging and dump a stack trace when debug
		 * is on.
		 *
		 * @link http://api.cakephp.org/class/error-handler
		 *
		 * @param string $template the error to show
		 * @access public
		 * 
		 * @return mixed void
		 */
		public function _outputMessage($template) {
			$this->controller->layout = 'error';
			
			$this->controller->params['url'] = isset($this->controller->params['url']) ? $this->controller->params['url'] : array('unknown');
			$this->log('Where:   ' . serialize($this->controller->params['url']), 'page_errors');
			$this->log('What:    ' . serialize($this->controller->viewVars), 'page_errors');
			$this->log('Referer: ' . $this->controller->referer(), 'page_errors');

			$this->controller->render($template);
			$this->controller->afterFilter();
			echo $this->controller->output;

			if(!Configure::read('debug')){
				return;
			}

			$backtrace = debug_backtrace(false);
			
			echo '<table width="80%" style="margin:auto;"><tr><th>File</th><th>Code</th><th>Line</th></tr>';
			$rows = array();

			foreach($backtrace as $_b){
				$_b = array_merge(
					array(
						'file' => '?',
						'class' => '?',
						'type' => '?',
						'function' => '?',
						'line' => '?'
					),
					$_b
				);
				$rows[] = '
					<tr>
						<td>' . $_b['file'] . '</td>' .
						'<td>' . $_b['class'] . $_b['type'] . $_b['function'] . '</td>' .
						'<td>' . $_b['line'] . '</td>' .
					'</tr>';
			}

			$rows = array_reverse($rows);
			echo implode('', $rows), '</table>';
		}
	}