<?php
/**
 * FilterHelper
 *
 * @package Infinitas.Filter.Helper
 */

/**
 * FilterHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Filter.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class FilterHelper extends AppHelper {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Form',
		'Html',
		'Assets.Image'
	);

/**
 * Count
 *
 * @todo remove, cant see the use
 *
 * @var integer
 */
	public $count = 0;

/**
 * build a search form
 *
 * @param string $model the model for the search form
 * @param array $filter the fields for searching
 *
 * @return string
 */
	public function form($model, $filter = array()) {
		if (empty($filter) || !isset($filter['fields'])) {
			$this->errors[] = 'There is no filters';
			return false;
		}

		$output = '<div class="filter-form"><h1>'.__d('filter', 'Search').'</h1>';
		foreach ($filter['fields'] as $field => $options) {
			if (is_array($options)) {
				switch($field) {
					case 'active':
						$emptyText = __d('filter', 'status');
						break;

					default:
						$emptyText = __d('filter', $field);
						break;
				}

				$emptyText = $this->Html->stripPluginName($emptyText);
				$emptyText = __d('filter', 'Select a %s', Inflector::humanize(str_replace('_id', '', $emptyText)));
				$output .= $this->Form->input($field, array(
						'type' => 'select',
						'div' => false,
						'options' => $options,
						'empty' => $emptyText,
						'label' => false,
						'required' => false
					));
			} else if (strstr($options, 'date')) {
				$output .= $this->Html->datePicker(array($options));
			} else{
				$output .= $this->Form->input($options, array(
					'type' => 'text',
					'div' => false,
					'label' => false,
					'required' => false,
					'placeholder' => Inflector::humanize($options)
				));
			}
		}

		$output .= $this->Form->button(
			$this->Image->image(
				'actions',
				'filter',
				array(
					'width' => '16px'
				)
			),
			array(
				'value' => 'filter',
				'name' => 'action',
				'title' => $this->niceTitleText('Filter results'),
				'div' => false
			)
		);
		$output .= '</div>';
		return $output;
	}

/**
 * Generate the clear filter links
 *
 * @param string $filter the url
 * @param boolean $div
 *
 * @return string
 */
	public function clear($filter, $div = false) {
		if (!isset($filter['url'][0]) || empty($filter['url'][0]) || $filter['url'][0] == '/') {
			$filter['url'][0] = '/';
		}

		$out = '';
		if ($div) {
			$out .= '<div class="filter">';
		}

		$out .= '<div class="wrap">';
		$parts = explode( '/', $filter['url'][0] );
		$done = array();

		foreach ($parts as $_f) {
			if (empty($_f) || in_array($_f, $done)) {
				continue;
			}

			$done[] = $_f;

			$text = explode('.', current(explode(':', $_f)));
			$text = (count($text ) > 1) ? $text[1] : $text[0];

			$text = $this->stripPluginName($text);
			if (substr($text, -3) == '_id') {
				$text = substr($text, 0, -3);
			}
			$out .= '<div class="left">'.
						'<div class="remove">'.
								$this->Html->link(
								Inflector::humanize($text),
								str_replace($_f, '', '/' . $this->request->url)
							).
						'</div>'.
					'</div>';
		}
		$out .= '</div>';
		if ($div) {
			$out .= '</div>';
		}

		return $out;
	}

/**
 * Alphabet filter list
 *
 * bulid a list of leters and numbers with filtered links to rows that
 * start with that letter or number
 *
 * @param string $model the model if not using the default
 *
 * @return string
 */
	public function alphabetFilter($model = null) {
		if (empty($model)) {
			if (empty($this->request->params['models'])) {
				return false;
			}
			$model = implode('.', current($this->request->params['models']));
		}

		$letters = ClassRegistry::init($model)->getLetterList();

		$return = array();
		foreach ($letters as $key => $value) {
			$url = ($value == true) ? $this->__filterLink($model, $key) : $key;
			if (is_array($url)) {
				$url = $this->Html->link(
					$key,
					InfinitasRouter::url($url),
					array(
						'title' => sprintf(__d('filter', 'Rows starting with "%s"'), $key)
					)
				);
			}
			$return[] = sprintf('<li>%s</li>', $url);
		}

		$return[] = sprintf('<li>%s</li>', $this->Html->link('All', $this->cleanCurrentUrl()));

		return '<div class="alphabet-filter"><ul>' . implode('', $return) . '</ul></div>';
	}

/**
 * Get filter link array url
 *
 * @param string $model the model the filter is for
 * @param string $text the text to show/filter with
 *
 * @return array
 */
	private function __filterLink($model, $text = null) {
		if (!$text) {
			return false;
		}

		$filter = array(
			ClassRegistry::init($model)->alias . '.' . ClassRegistry::init($model)->displayField => $text
		);

		$params = array_merge(parent::cleanCurrentUrl(), $this->request->params['named'], $this->request->params['pass'], $filter);

		return $params;
	}

}