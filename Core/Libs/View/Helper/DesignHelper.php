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
		if (isset($class['div']) && $class['div']) {
			if (is_bool($class['div'])) {
				unset($class['div']);
			}

			if ($div === null) {
				$div = true;
			}
		}
		if (isset($class['div_id']) && $class['div_id']) {
			if ($div === null) {
				$div = true;
			}
		}
		if (!is_array($class)) {
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
		if ($div) {
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
		if (count($tabs) != count($content)) {
			throw new InvalidArgumentException('Tab count does not match content');
		}

		$uuid = String::uuid();
		$i = 0;
		foreach ($tabs as $k => $tab) {
			if (!is_array($tab)) {
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

/**
 * Render sidebar markup for forms
 *
 * Valid options inclode:
 *	- title: this is the tag to render, defaults to H4
 *	- hr: boolean, true (default) to render a hr below the title
 *	- any other options that HtmlHelper::tag() can use
 *
 * @param array|sring $content the content for the side bar, arrays are imploded
 * @param string $title optional title ("details" used as default) translated with current plugin
 * @param array $options the options for rendering
 *
 * @return string
 */
	public function sidebar($content, $title = null, array $options = array()) {
		$options = array_merge(array(
			'title' => 'h4',
			'hr' => true,
			'class' => 'span3 sidebar'
		), $options);

		$title = $this->_title($title, $options);

		if (is_array($content)) {
			$content = implode('', $content);
		}

		return $this->Html->tag('div', $title . $content, $options);
	}

	public function dashboard($content, $title = null, array $options = array()) {
		$options = array_merge(array(
			'title' => 'h1',
			'class' => 'dashboard',
			'hr' => true,
			'info' => null,
			'alert' => null
		), $options);

		$title = $this->_title($title, $options);
		$content = implode('', (array)$content) . self::info($options['info']) . self::alert($options['alert']);

		return $this->Html->tag('div', $title . $content, $options);
	}

	public function info($info, array $options = array()) {
		if (empty($info)) {
			return null;
		}
		$options = array_merge(array(
			'class' => 'info',
			'title' => 'p'
		), $options);
		return $this->Html->tag('p', implode('', (array)$info), $options);
	}

	public function alert($info, array $options = array()) {
		if (empty($info)) {
			return null;
		}
		$options = array_merge(array(
			'class' => 'alert',
			'title' => 'p'
		), $options);
		return $this->Html->tag('p', implode('', (array)$info), $options);
	}

/**
 * Build title markup for various boxes
 *
 * Options:
 *	- title: this is the tag used when building the markup
 *	- hr: set to true to render a <hr> tag below the title element
 *
 * All options this method uses will be removed from the passed in options so the
 * options can later be used without interference.
 *
 * @param string $title the title text
 * @param array $options the title options
 *
 * @return string
 */
	protected function _title($title, array &$options) {
		$options = array_merge(array(
			'title' => null,
			'hr' => null
		), $options);
		if (!$title) {
			$title = __d($this->request->params['plugin'], 'Details');
		}
		if (!empty($options['title'])) {
			$title = $this->Html->tag($options['title'], $title);
		}

		if ($options['hr']) {
			$title .= $this->Html->tag('hr');
		}
		unset($options['title'], $options['hr']);
		return $title;
	}

	public function icon($options = array()) {
		if(!is_array($options)) {
			$options = array('icon' => $options);
		}
		$options = array_merge(array(
			'size' => null,
			'icon' => null
		), $options);

		$icon = $this->_icon($options['icon']);
		if (!$icon) {
			return null;
		}
		return $this->Html->tag('i', '', array(
			'class' => $icon
		));
	}

	public function count($count, $type = null) {
		if(!$count) {
			return '-';
		}
		return $this->Html->tag('span', (int)$count, array(
			'class' => array(
				'badge',
				$type ? 'badge-' . $type : null
			)
		));
	}

	protected function _icon($icon) {
		switch ($icon) {
			case 'delete':
				$icon = 'remove-circle';
				break;

			case 'reply':
				$icon = 'share-alt';
				break;

			case 'toggle':
				$icon = 'refresh';
				break;

			case 'spam':
				$icon = 'exclamation-sign';
				break;

			case 'add':
				$icon = 'plus';
				break;

			case 'cancel':
				$icon = 'remove-sign';
				break;

			case 'up':
			case 'down':
				$icon = 'circle-arrow-' . $icon;
				break;

			case 'preview':
				$icon = 'external-link';
				break;

			case 'active':
				$icon = 'ok-circle';
				break;

			case 'inactive':
				$icon = 'off';
				break;

			case 'locked':
				$icon = 'lock';
				break;

			case 'unlocked':
				$icon = 'unlock';
				break;

			case 'hidden':
				$icon = 'eye-close';
				break;
		}

		$icon = 'icon-' . $icon;
		if ($icon == 'icon-') {
			return null;
		}

		return $icon;
	}

}