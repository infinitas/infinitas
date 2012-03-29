<?php
	class GlobalContentsHelper extends AppHelper {
		public $helpers = array(
			'Events.Event'
		);
		
		/**
		 * @brief generate a link to view an author
		 * 
		 * @param <type> $data
		 * @return <type>
		 */
		public function author($data) {
			if(empty($data['ContentAuthor']['id']) && empty($data['GlobalContent']['author_alias'])) {
				return false;
			}

			$author = !empty($data['GlobalContent']['author_alias']) ? $data['GlobalContent']['author_alias'] : $data['ContentAuthor']['username'];
			
			return $this->Html->link(
				$author,
				array(
					'plugin' => 'contents',
					'controller' => 'global_authors',
					'action' => 'view',
					'author' => $author
				)
			);
		}

		public function renderTemplate($data = array()) {
			if(empty($data['Layout']) && empty($data['GlobalLayout'])) {
				throw new Exception('Can not find template to render');
			}

			$return = $this->__renderCss($data);
			$return .= $this->__renderHtml($data);
		}
		
		public function url($data) {
			list($plugin, $model) = pluginSplit($data['GlobalContent']['model']);
			
			$event = $this->Event->trigger($plugin . '.slugUrl', $data);
			
			return current($event['slugUrl']);
		}

		private function __renderCss($data) {
			if(!empty($data['Layout']['css'])){
				return sprintf('<style type="text/css">%s</style>', $data['Layout']['css']);
			}

			if(!empty($data['GlobalLayout']['css'])){
				return sprintf('<style type="text/css">%s</style>', $data['Layout']['css']);
			}
		}

		private function __renderHtml($data) {
			if(!empty($data['Layout']['html'])){
				echo $data['Layout']['html'];
			}

			if(!empty($data['GlobalLayout']['html'])){
				echo $data['GlobalLayout']['html'];
			}
		}
	}