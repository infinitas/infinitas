<?php
	class GlobalContentsHelper extends AppHelper {
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
	}