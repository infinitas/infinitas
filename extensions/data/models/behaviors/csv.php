<?php
	ini_set('auto_detect_line_endings', true);

	/**
	 *
	 * http://www.csvreader.com/csv_format.php
	 */
	class CsvBehavior extends ModelBehavior {
		/**
		 * default settings.
		 *
		 * @var array
		 */
		var $_defaults = array(
			'delimiter' => ',',
			'newline' => "\n\r",
			'enclosure' => '"',
			'escape' => '\\',
			'action' => 'download',
			'new_line' => '{nl}',
			'ignore' => array(
				'created',
				'modified',

				'locked',
				'locked_by',
				'locked_since',

				'deleted',
				'deleted_date'
			)
		);

		/**
		 * internal csv data
		 *
		 * @var array
		 */
		var $_csv = array();

		/**
		 * setup the behavior
		 *
		 * @param mixed $Model
		 * @param mixed $config
		 * @return
		 */
		function setup(&$Model, $config = null) {
			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			} else {
				$this->settings[$Model->alias] = $this->_defaults;
			}

			$this->_csv[$Model->alias] = array();
			$this->Model = $Model;

		}

		/**
		 * shortcut for export from existing data array
		 *
		 * Takes an array from a find all and passes it in the correct format to export()
		 *
		 * @param obj $Model the current model object
		 * @param array $data the data to convert to csv
		 *
		 * @return string the csv data ready to write to a file.
		 */
		function exportFromData(&$Model, $data = array()){
			return $this->export(&$Model, 'data', array('data' => $data));
		}

		/**
		 * shortcut for export from model
		 *
		 * Takes an array from a find all and passes it in the correct format to export()
		 */
		function exportFromModel(&$Model, $exportModel, $conditions){
			return $this->export(&$Model, 'model', array('model' => $exportModel, 'conditions' => $conditions));
		}

		/**
		* Export data to csv from an array or a model.
		*
		* If data is passed, the data will be converted to csv data, if a model name is
		* passed the model is imported and a find('all) is run with conditions if there
		* are any.
		*
		* @param obj $Model the current model object
		* @param string $type the type of export we are doing, model or data
		* @param array params, contains the data or model and conditions
		*
		* @return string the csv data that was converted or false.
		*/
		function export(&$Model, $type = null, $_params){
			$params = array_merge(
				array(
					'model' => null,
					'conditions' => array(),
					'data' => array()
				),
				$_params
			);
			switch($type){
				case 'model':
					if (isset($params['model'])) {
						$params['data'] = ClassRegistry::init($params['model'])->find('all', $params['conditions']);
					}
					else{
						$params['data'] = $Model->find('all', $params['conditions']);
					}
				// dont break here, it must do the next bit.

				case 'data':
					if (empty($params['data'])) {
						return false;
					}

					return $this->_writeCsvData($params);
					break;

				default:
					return false;
			} // switch
		}

		/**
		* get the head and data that needs to be converted and do the conversion
		*
		* @params array $params the params with data to be converted
		*
		* @return string the csv data
		*/
		function _writeCsvData($params){
			$this->_writeHeader($params['data']);
			$this->_writeData($params['data']);

			$return  = implode(',', $this->_csv[$this->Model->alias]['header'])."\n";
			$return .= implode("\n", $this->_csv[$this->Model->alias]['data']);
			pr($return);
			return $return;
		}

		/**
		* Get the headers
		*
		* Get the headers for the model and relations based on the data that was
		* passed in.
		*
		* @param array $rows the data being used
		*
		* @return null a class var is set
		*/
		function _writeHeader($rows){
			foreach($rows as &$data){
				foreach($data as $model => $row){
					foreach($row as $field => $value){
						if (is_array($row[$field])) {
							$this->_writeHeaderRelation(array($field => $row[$field]));
						}
						else if (!in_array($field, $this->settings[$this->Model->alias]['ignore'])){
							 $this->_csv[$this->Model->alias]['header'][] = $model.'.'.$field;
						}
					}
					break;
				}
				break;
			}
		}

		/**
		 * Get the header (related models)
		 *
		 * Get the headers for the model and relations based on the data that was
		 * passed in for the relations. should be able to handel contained data
		 *
		 * @param array $datas the data of the relation
		 *
		 * @return null a class var is set
		 */
		function _writeHeaderRelation($datas){
			foreach($datas as $model => $row){
				foreach($row as $field => $value){
					if (is_array()) {
						$this->_writeHeaderRelation(array($field => $row[$field]));
					}

					if (!in_array($field, $this->settings[$this->Model->alias]['ignore'])){
						$this->_csv[$this->Model->alias]['header'][] = $model.'.'.$field;
					}
				}
				break;
			}
		}

		/**
		 * Get the data
		 *
		 * Simmilar to gettin headers but this method converts the actual data.
		 *
		 * @param array $rows the data to be converted to csv
		 */
		function _writeData($rows){
			foreach($rows as &$data){
				foreach($data as $model => $row){
					$dataToWrite = array();
					foreach($row as $field => $value){
						if (is_array($row[$field])) {
							$relations[] = $this->_writeDataRelation(array($field => $row[$field]));
						}
						else{
							$dataToWrite[] = $this->_escape($value);
						}
					}
					$this->_csv[$this->Model->alias]['data'][] = implode($this->settings[$this->Model->alias]['delimiter'], $dataToWrite);
				}
			}
		}

		/**
		 * Get the data
		 *
		 * Simmilar to gettin headers but this method converts the actual data.
		 *
		 * @param array $rows the data to be converted to csv
		 */
		function _writeDataRelation($row){
			foreach($row as $model => $data){
				$dataToWrite = array();
				foreach($data as $field => $value){
					if (is_array($row[$field])) {
						$dataToWrite[] = $this->_writeDataRelation(array($field => $data[$field]));
					}
					$dataToWrite[] = $this->_escape($value);
				}
				$this->_csv[$this->Model->alias]['data'][] = implode($this->settings[$this->Model->alias]['delimiter'], $dataToWrite);
			}
		}

		/**
		* escape a field.
		*
		* This will escape a field from the database and make it safe for csv
		* output.
		*
		* @param string $value the field to escape.
		*
		* @return string the escaped field.
		*/
		function _escape($value){
			if (!is_string($value)) {
				return 'Error: '.serialize($value);
			}

			$value = trim($value);
			$value = trim($value, '"');

			$search = array(',',    '\\',   '"');
			$replace = array('\\,', '\\\\', '""');
			$value = str_replace($search, $replace, $value);

			$value = str_replace(array("\r\n", "\n", "\r"), $this->settings[$this->Model->alias]['new_line'], $value);

			return $this->settings[$this->Model->alias]['enclosure'].$value.$this->settings[$this->Model->alias]['enclosure'];
		}
	}
?>