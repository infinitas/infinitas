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
		// core helpers
		'Libs.Geshi',
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
	* a wrapper for each item.
	*
	* @param string $class a css class to append
	* @return string :: html code to start the block
	*/
	function indexPostStart($class = '') {
		return '<div class="post ' . $this->layout . ' ' . $class . '">';
	}

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
	* Show the intro to the blog post
	*
	* options for config are
	* - hilight will use geshi to highlight sorce code.
	* - truncate sets options for the truncate.
	*    - truncate == (int) then uses cakes default
	*    - truncate == (array) then you can set params like truncate uses
	*    - config == null, no hilighting or truncat will happen.
	*
	* @param array $config
	* @return string html code
	*/
	function indexPostContent($config = array()) {
		$out = '<div class="content ' . $this->layout . '">';
		$body = $this->data['Post']['intro'];
		if ($this->indexConfig['truncate']) {
			if (!is_array($this->indexConfig['truncate'])) {
				$this->indexConfig['truncate']['length'] = $this->indexConfig['truncate'];
			}

			$body = $this->Text->truncate($this->data['Post']['intro'], $this->indexConfig['truncate']['length'], $this->indexConfig['truncate']);
		}

		if ($this->indexConfig['highlight']) {
			$this->Geshi->highlight($body);
		}

		$out .= '<p>' . $body . '</p>' .
		'<div class="tags">' . $this->tags($this->data['Tag']) . '</div>' .
		'</div>';

		return $out;
	}

	/**
	* generate some links for the post
	*
	* takes the following
	*   - print :: a javascript pring link
	*   - comment :: shows count and opens the post by the comments
	*   - more :: show / view more
	*
	* @todo -c"LayoutHelper" Implement LayoutHelper.
	*   - pdf
	*   - print css
	*   - share on fb
	*   - retweet on twitter
	*   - email to a friend
	* @param array $params
	* @return
	*/
	function indexPostFooter($params = array('print', 'comment', 'more')) {
		if (!is_array($params)) {
			$params = array($params);
		}

		$out = '<div class="footer">' .
		'<ul>';
		foreach($params as $param) {
			switch($param) {
				case 'print':
					$out .= '<li class="printerfriendly"><a href="#">Printer Friendly</a></li>';
					break;

				case 'comment':
					$out .= '<li class="comments">' .
					$this->Html->link(
						sprintf('%s ( %s )', __('Comments', true), $this->data['Post']['comment_count']),
						$this->Blog->slugLink($this->data['Post'], $this->data['Category']['slug'], null, false) + array('#' => 'comments')
						) .
					'</li>';
					break;

				case 'more':
					$out .= '<li class="readmore">' .
						$this->Html->link(
							__('Read more', true),
							$this->Blog->slugLink($this->data['Post'], $this->data['Category']['slug'], null, false)
						).
					'</li>';
					break;
			} // switch
		}
		$out .=
		'</ul>' .
		'</div>';

		return $out;
	}

	/**
	* just a simple div end for the wrapper
	*
	* also unsets the current data in the helper
	*
	* @return string </div>
	*/
	function indexPostEnd() {
		$this->unsetData();
		return '</div>';
	}

	/**
	* Methods to show a fulll post
	*/

	/**
	* The head of a post.
	*
	* Sets options to show the date comments and the user etc.
	*
	* @param array $ params what to show in the view
	* @param string $ seperator how to seperate the elements in the head.
	* @return string some html code
	*/
	function viewPostHead($params = array('comments', 'date', 'views'), $seperator = ' :: ') {
		$this->Blog->metaData($this->data);

		$temp = array();

		$out = '<h1>' . $this->data['Post']['title'] . '</h1>';
		$out .= '<p><small>';
		foreach($params as $param) {
			switch($param) {
				case 'date':
					$temp[] = sprintf('%s: %s', __('Created', true), $this->Time-> {
							$this->dateFormat}
						($this->data['Post']['created']));
					break;

				case 'comments':
					$temp[] = sprintf('%s ( %s )', __('Comments', true), $this->data['Post']['comment_count']);
					break;

				case 'views':
					$temp[] = sprintf('%s ( %s )', __('Views', true), $this->data['Post']['views']);
					break;
			} // switch
		}
		if (!empty($temp)) {
			$out .= implode($seperator, $temp);
		}

		$out .= '</small></p>';

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

?>