<?php
/**
 * WebmasterRedirect
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link http://infinitas-cms.org/Webmaster
 * @package	Webmaster.Model
 * @license	http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 */

class WebmasterRedirect extends WebmasterAppModel {

/**
 * The display field for select boxes
 *
 * @var string
 */
	public $displayField = 'url';

/**
 * custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'isRecorded' => true,
		'record' => true
	);

/**
 * Constructor
 *
 * @param string|integer $id string uuid or id
 * @param string $table the table that the model is for
 * @param string $ds the datasource being used
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->virtualFields['empty_redirect'] = sprintf('CASE WHEN %s.redirect_to = "" OR %s.redirect_to IS NULL THEN 1 ELSE 0 END', $this->alias, $this->alias);

		$this->validate = array(
		);
	}

/**
 * find if the url has been recorded previously or not
 * 
 * @param string $state before / after
 * @param array $query the find query params
 * @param array $results the find results
 * 
 * @return array
 */
	protected function _findIsRecorded($state, array $query, array $results = array()) {
		if ($state == 'before') {
			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.url' => $query[0]
			));

			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.ignore',
			);
			return $query;
		}

		if (empty($results)) {
			return false;
		}

		if (array_key_exists('ignore', $results[0][$this->alias]) && $results[0][$this->alias]['ignore']) {
			return null;
		}

		return $results[0][$this->alias][$this->primaryKey];
	}

/**
 * find a record for a page error
 * 
 * @param string $state before / after
 * @param array $query the find query params
 * @param array $results the find results
 * 
 * @return array
 */
	protected function _findRecord($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				throw new InvalidArgumentException(__d('webmaster', 'Invalid url specified'));
			}
			$query['conditions'] = array_merge((array)$query['conditions'], array(
				'or' => array(
					$this->alias . '.id' => $query[0],
					$this->alias . '.url' => $query[0]
				)
			));

			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField,
				$this->alias . '.error_count',
				$this->alias . '.redirect_to',
				$this->alias . '.redirect_permanent',
				$this->alias . '.redirect_message',
				$this->alias . '.redirect_count',
				$this->alias . '.ignore',
			));
			$query['limit'] = 1;
			return $query;
		}

		if (empty($results[0])) {
			return array();
		}

		return $results[0];
	}

/**
 * record the fact that a url has been found broken
 *
 * @param array $data the url data (request object array)
 *
 * @return array
 */
	public function recordError(array $data) {
		if (empty($data['here'])) {
			return false;
		}

		$recorded = $this->find('isRecorded', $data['here']);
		if ($recorded === false) {
			if (empty($data['here'])) {
				return false;
			}
			return $this->record($data);
		}
		
		return $this->increment($recorded);
	}

/**
 * Increment the counts
 *
 * If the url is already recorded previously, the counter is updated. This helps admins find
 * urls that are causing more problems for site users.
 *
 * If the url has a redirect_to address configured, the redirect_count will be incremented too
 * indicating a successful redirect.
 *
 * @param array $id the id (or url) of the record to update
 *
 * @return array
 */
	public function increment($id) {
		$record = $this->find('record', $id);

		$record = $record[$this->alias];
		return $this->save(array_merge($record, array(
			'error_count' => $record['error_count'] + 1,
			'modified' => false,
			'redirect_count' => $record['redirect_count'] += (int)(bool)$record['redirect_to']
		)));
	}

/**
 * Record a new broken url
 *
 * If the url is not found it is stored with the initial blanks. Only once the redirect_to has been set
 * will the plugin start redirecting users to the new page
 *
 * @param array $id the id (or url) of the record to update
 *
 * @return array
 */
	public function record(array $data) {
		$this->create();
		$saved = $this->save(array(
			'error_count' => 0,
			'url' => $data['here']
		));

		if ($saved) {
			return $this->increment($this->id);
		}
	}
}
