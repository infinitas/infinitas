<?php
	/**
	* Blog Helper class file.
	*
	* varios methods for use in the blog app
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
	* @subpackage blog.views.helpers.blog
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	*/
	class BlogHelper extends AppHelper {
		var $helpers = array(
			// cake helpers
			'Html', 'Form', 'Text', 'Time',
			// core helpers
			'Libs.Image', 'Libs.Design', 'Libs.Wysiwyg'
		);

		/**
		* Date format from {@see TimeHelper}
		*/
		var $dateFormat = 'niceShort';

		/**
		* the layout style.
		*
		* @param string $
		*/
		var $layout = 'list';

		/**
		* Internal list of errors
		*
		* @param array $ of errors
		*/
		var $errors = array();

		/**
		* Internal use of the post.
		*/
		var $data = array();

		var $showIntro = true;

		/**
		* generate metadata.
		*
		* takes an array of posts and generates data for the page.  Can use
		* a finr( 'all' ) or find( 'first' ) to generate the meta tags.
		*
		* Takes the tags from the post and generates keywords.
		*
		* Takes the body of the post and generates a description based on the
		* tags using excerpt.
		*
		* @todo -c"BlogHelper" Implement BlogHelper.
		*   - do something if there is no tags
		*   - do something when there is no description
		*   - find out more about seo
		*
		* this method simply echos the metadata.
		* @param array $posts from a ->find() call
		* @return bool false when no data passed
		*/
		function metaData($posts = array()) {
			if (!isset($posts[0])) {
				if (!isset($posts)) {
					return false;
				}

				$posts = array($posts);
			}

			foreach($posts as $post) {
				$tags = Set::extract('/Tag/name', $post);
				$keywords = implode(',', $tags);

				$description = array();

				foreach($tags as $tag) {
					$description[] = $this->Text->excerpt($post['Post']['body'], $tag, 50);
				}

				shuffle($description);

				echo $this->Html->meta('keywords', $keywords);
				echo $this->Html->meta('description', substr(str_replace('...', '', implode(' ', $description)), 0, 255));
			}

			return true;
		}

		function formatUrl($url) {
			// if has http(s):// just return
			// if only www. add http:// and return
			// if only site.com add http://www. and return
			return $url;
		}

		/**
		* trying to implement something like cakes Text::autoLinkUrls() that
		* will automaticaly put <b> tags around keywords in the posts.
		*/
		function highlightTags($text, $tags = null) {
			if (!$tags) {
				return $text;
			}

			$linkOptions = 'array()';
			$tags = '#(' . implode('|', $tags) . ')#';

			return preg_replace_callback($tags,
				create_function(
					'$matches',
					'return "<b>".$matches[0]."</b>";'
					),
				$text
				);
		}


		/**
		 * Create Pagination for linked posts.
		 *
		 * This will generate a list of links of all posts that are together. If the
		 * parent and child posts are empty its assumed that the post does not have any
		 * more linked posts to display so it will just return.
		 *
		 * @param mixed $post the data from ->read()
		 * @return mixed $out is the links or '' for non linked posts
		 */
		function pagination($post){
			if (empty($post['ParentPost']['id']) && empty($post['ChildPost']['id'])) {
				return '';
			}
			$this->currentCategory = $post['Category']['slug'];

			$out = '<ul>';

			if (empty($post['ParentPost']['id'])) {
				$out .= '<li>';
				$out .= $this->slugLink($post['Post']);
				$out .= '</li>';

				foreach($post['ChildPost'] as $child ){
					$out .= '<li>';
					$out .= $this->slugLink($child);
					$out .= '</li>';
				}
			}
			else{
				$out .= '<li>';
				$out .= $this->slugLink($post['ParentPost']);
				$out .= '</li>';

				foreach($post['ParentPost']['ChildPost'] as $child ){
					$out .= '<li>';
					$out .= $this->slugLink($child['Post']);
					$out .= '</li>';
				}
			}
			$out .= '</ul>';
			return $out;
		}


		/**
		 * Create blog slugs
		 *
		 * creates custom sluged urls for the posts. includes the post category and
		 * the post tile.
		 *
		 * @param mixed $data the post data (from ->read() but only $post['Post'])
		 * @param mixed $category the category of the post
		 * @param string $class a class to append to the link that is active
		 * @param mixed $link true for a <a> link false for array() url
		 *
		 * @return mixed if $link is true a link will be returned else a url in array format
		 */
		function slugLink($data, $category = null, $class = 'current', $link = true){
			if (isset($this->params['id']) && $this->params['id'] !== $data['id']) {
				$class = '';
			}

			if (!isset($this->currentCategory)) {
				$this->currentCategory = $category;
			}

			$url = array(
				'plugin' => 'blog',
				'controller' =>'posts',
				'action' => 'view',
				'category' => $this->currentCategory,
				'slug' => $data['slug'],
				'id' => $data['id']
			);

			if ($link) {
				return $this->Html->link(
					$data['title'],
					$url,
					array(
						'class' => $class,
						'title' => $data['title']
					)
				);
			}
			return $url;
		}
	}
?>