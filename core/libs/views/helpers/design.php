<?php
	/**
	*/
	class DesignHelper extends AppHelper {
		public $helpers = array('Text', 'Html');

		public function arrayToList($array = array(), $class = null, $div = false) {
			if(!empty($class['div'])) {
				$div = true;
			}
			if(!is_array($class)) {
				$class = array(
					'div' => $class,
					'div-id' => null,
					'ul' => $class,
					'ul-id' => null,
					'li' => null
				);
			}
			$class = array_merge(
				array('div' => null, 'div_id' => null, 'ul' => null, 'ul_id' => null, 'li' => null),
				$class
			);
			$base = '%s';
			if($div){
				$base = '<div id=":div_id" class=":div">%s</div>';
			}

			$base = sprintf(
				$base,
				sprintf(
					'<ul id=":ul_id" class=":ul"><li class=":li">%s</li></ul>',
					implode('</li><li class=":li">', (array)$array)
				)
			);

			return String::insert($base, $class);
		}

		public function infoBox($info = array(), $truncate = 24) {
			list($heading, $data, $link, $image) = $info;

			if (empty($data)) {
				$this->errors[] = 'No data supplied';
				return false;
			}

			$out = '<div class="infoBox">';
			$out .= '<div class="heading">' . $heading . '</div>';
			$out .= '<div class="body">';
			foreach($data as $id => $text) {
				if ($text == '' || empty($text) || !$text) {
					continue;
				}

				$text = $this->Text->truncate($text, $this->__adjustTruncate($truncate, $image, $link));

				if ($image) {
					list($imagePath, $url, $params) = $image;
					$url = $this->__createUrl($url, $id);

					$urlParams = $params;
					unset($urlParams['alt']);

					$out .= $this->Html->link($this->Html->image($imagePath, $params) . ' ' . $text,
						$url,
						$urlParams + array('escape' => false)
					);
				}

				else if ($link) {
					list($url, $params) = $link;
					$url = $this->__createUrl($url, $id);

					unset($params['alt']);
					$out .= $this->Html->link($text,
						$url,
						$params
						);
				}

				else if ($data) {
					$out .= $text;
				}
			}

			$out .= '</div>';
			$out .= '</div>';

			return $out;
		}

		public function quickLink($info = array(), $truncate = 24) {
			if (count($info) < 2 || empty($info)) {
				$this->errors[] = 'No data to generate links';
				return false;
			}

			list($link, $url, $params) = $info;

			$link = $this->__niceText($link, $truncate);

			if (empty($link)) {
				$this->errors[] = 'No text for the link supplied';
				return false;
			}

			return $this->Html->link($link,
				$url,
				(array)$params + array('class' => 'quickLink')
			);
		}

		public function tabs($tabs, $content) {
			if(count($tabs) != count($content)) {
				throw new Exception('Tab count does not match content');
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

		private function __createUrl($url = null, $id = 0) {
			if (!$url) {
				$this->errors[] = 'No url passed, returning root';
				return '/';
			}

			if (is_array($url)) {
				$url = $url + array($id);
			}

			else {
				$url = rtrim($url, '/');
				$url .= '/' . $id;
			}

			return $url;
		}

		private function __niceText($text = '', $truncate = false) {
			$text = Inflector::humanize($text);
			if ($truncate) {
				$text = $this->Text->truncate($text, $truncate);
			}

			return $text;
		}

		private function __adjustTruncate($truncate = false, $image = false, $link = false) {
			if (!$truncate) {
				return $truncate;
			}
			// decrease a bit for the image
			if ($image) {
				$truncate = $truncate - 5;
			}
			// increase a bit cos there is no link
			else if (!$link) {
				$truncate = $truncate + 5;
			}

			return $truncate;
		}
	}