<?php
/**
	* Post Laout Helper class file.
	*
	* Makes modifying the layout of the site via admin posible
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	*
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package blog
	* @subpackage blog.views.helpers.postLayout
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	*/
	class PostLayoutHelper extends BlogHelper {
		var $helpers = array(
			// cake helpers
			'Time', 'Text', 'Html',
			// blog helpers
			'Blog.Blog'
			);

		/**
		* Some config options for the index page
		*/
		var $indexConfig = array(
			'highlight' => false,
			'truncate' => array(
				'length' => 100,
				'ending' => '...',
				'html' => false
				)
			);

		/**
		* Methods to show the index page.
		*/

		/**
		* Display the head of the post block
		*
		* @return string :: html code for the post head
		*/
		function indexPostHead() {
			$out = '<div class="header"><h3>';
			$out .= $this->Html->link(
				$this->data['Post']['title'],
				array(
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => 'view',
					(!empty($this->data['Post']['slug'])) ? $this->data['Post']['slug'] : $this->data['Post']['id']));
			$out .= '</h3>';
			$out .= '<div class="date">' . $this->Time-> {
				$this->dateFormat}
			($this->data['Post']['created']) . '</div>';
			$out .= '</div>';

			return $out;
		}

		/**
		* the main body of the post.
		*
		* proccsses the body text to display in the view.
		*
		* @param array $params what you want to do the the body.
		* - hilight : will hilight code in <pre> tags from Geshi
		* - strip : will strip html tags.
		* @return string some html code
		*/
		function viewPostBody($params = array('highlight')) {
			$out = '';

			if ($this->showIntro) {
				$out .= '<p><i>' . strip_tags($this->data['Post']['intro']) . '</i></p>';
			}

			$out .= '<p>';
			$body = $this->data['Post']['body'];

			foreach($params as $param) {
				switch($param) {
					case 'highlight':
						$body = $this->Geshi->highlight($body);
						break;

					case 'strip':
						$body = strip_tags($body);
						break;
				} // switch
			}
			$out .= $body;

			$out .= '</p>';

			return $out;
		}

		/**
		* Tag list links.
		*
		* Generates a list of links for the tags of the post
		*
		* @param array $tags normaly from $post['Tag']
		* @param string $seperator what to seperate the links with
		* @return string the html code for the linked tags
		*/
		function tags($tags = array(), $seperator = ' :: ') {
			if (empty($tags)) {
				$this->errors[] = __('No tags supplied', true);
				return false;
			}

			$links = array();
			foreach($tags as $tag) {
				$links[] = $this->Html->link($tag['name'],
					array(
						'plugin' => 'blog',
						'controller' => 'posts',
						'action' => 'index',
						$tag['name']
						)
					);
			}

			return implode($seperator, $links);
		}

		/**
		* pennding posts box.
		*
		* Generates a box of some pending posts.
		*
		* @param array $pendingPosts the ->find(list) of pending posts
		* @return string the html code for the box of pending posts
		*/
		function pendingBox($pendingPosts = array()) {
			if (!empty($pendingPosts)) {
				$out = '<strong class="h">' .
				$this->Html->link(
					__('Pending Posts', true),
					array(
						'plugin' => 'blog',
						'controller' => 'posts',
						'action' => 'index',
						0
						)
					) .
				'</strong>';
				$out .= '<div class="box"><ul>';
				foreach($pendingPosts as $k => $title) {
					$out .= '<li>' . $this->Infinitas->toggle(0, $k) . ' ' . $title . '</li>';
				}
				$out .= '</ul></div>';
				return $out;
			}

			return false;
		}

		/**
		* most populat posts box.
		*
		* Generates a box of the most poular posts
		*
		* @todo -c"PostLayoutHelper" Implement PostLayoutHelper. make this work
		* in admin and frontend.  with look and feel.
		* @param array $pendingPosts the ->find(list) of popular posts
		* @return string the html code for the box of popular posts
		*/
		function mostPopular($postPopular = array()) {
			if (!empty($postPopular)) {
				$out = '<strong class="h">' .
				$this->Html->link(
					__('Popular Posts', true),
					array(
						'plugin' => 'blog',
						'controller' => 'posts',
						'action' => 'index',
						'sort' => 'views',
						'direction' => 'desc'
						)
					) .
				'</strong>';
				$out .= '<div class="box"><ul>';
				foreach($postPopular as $k => $title) {
					$out .= '<li>' .
					$this->Html->link($title,
						array(
							'plugin' => 'blog',
							'controller' => 'posts',
							'action' => 'view',
							$k,
							'admin' => false
							)
						) .
					'</li>';
				}
				$out .= '</ul></div>';
				return $out;
			}

			return false;
		}
	}