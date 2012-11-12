<?php
/**
 * DesignHelper to provide methods for building the interface
 */
class DesignHelper extends AppHelper {
/**
 * helpers that are used in this helper
 *
 * @var array
 */
	public $helpers = array(
		'Text',
		'Html'
	);

/**
 * convert an array to a list <li>
 *
 * @param array $array the data to convert
 * @param mixed $class the class / configs for the list
 * @param boolean $div Wrap the list in a div
 *
 * @return string
 */
	public function arrayToList(array $array, $class = null, $div = null) {
		if(isset($class['div']) && $class['div']) {
			if(is_bool($class['div'])) {
				unset($class['div']);
			}

			if($div === null) {
				$div = true;
			}
		}
		if(isset($class['div_id']) && $class['div_id']) {
			if($div === null) {
				$div = true;
			}
		}
		if(!is_array($class)) {
			$class = array(
				'div' => $class,
				'div_id' => null,
				'ul' => $class,
				'ul_id' => null,
				'li' => null
			);
		}
		$class = array_merge(
			array('div' => null, 'div_id' => null, 'ul' => null, 'ul_id' => null, 'li' => null),
			$class
		);
		$base = '%s';
		if($div) {
			$base = $this->Html->tag('div', '%s', array(
				'id' => ':div_id',
				'class' => ':div'
			));
		}

		$base = sprintf(
			$base,
			sprintf(
				$this->Html->tag('ul', $this->Html->tag('li', '%s', array('class' => ':li')), array(
					'id' => ':ul_id',
					'class' => ':ul'
				)),
				implode('</li><li class=":li">', (array)$array)
			)
		);


		return str_replace(array('id=""', 'class=""'), '', String::insert($base, $class));
	}

/**
 * generate a tab box
 *
 * @param array $tabs
 * @param array $content
 *
 * @return string
 *
 * @throws Exception
 */
	public function tabs(array $tabs, array $content) {
		if(count($tabs) != count($content)) {
			throw new InvalidArgumentException('Tab count does not match content');
		}

		$uuid = String::uuid();
		$i = 0;
		foreach($tabs as $k => $tab) {
			if(!is_array($tab)) {
				$tab = array('text' => $tab);
			}

			$tab = array_merge(
				array('text' => 'Missing', 'url' => '', 'config' => array()),
				$tab
			);

			$tabs[$k] = $this->Html->link(
				$tab['text'],
				$tab['url'] . sprintf('#%s-%d', $uuid, $i),
				$tab['config']
			);

			$content[$k] = sprintf('<div id="%s-%d">%s</div>', $uuid, $i, $content[$k]);

			$i++;
		}

		return sprintf(
			'<div class="tabs">%s%s</div>',
			$this->arrayToList($tabs),
			implode('', $content)
		);
	}
}