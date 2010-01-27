<?php
/**
* CommentLayout Helper class file.
*
* makes changing the layout via the admin possible.
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
* @subpackage blog.views.helpers.commentLayout
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*/
class CommentLayoutHelper extends BlogHelper {
	var $helpers = array(
		// cake helpers
		'Form', 'Time', 'Html',
		// core helpers
		'Libs.Geshi', 'Libs.Wysiwyg', 'Libs.Gravatar'
		);

	/**
	* Url to adding comments
	*/
	var $addCommentUrl = array(
		'url' => array(
			'plugin' => 'blog',
			'controller' => 'comments',
			'action' => 'add'
			)
		);

	/**
	* Set the data for the layout helper to use.
	*
	* {@see BlogHelper::setdata} for more information
	*
	* @param array $post
	* @return bool true
	*/
	function setData($data) {
		if (!isset($data['Comment'])) {
			$_temp['Comment'] = $data;
			unset($data);
			$data = $_temp;
		}
		return parent::setData($data);
	}

	/**
	* Unset the post data.
	*
	* {@see BlogHelper::unsetData} for more information
	*/
	function unsetData() {
		return parent::unsetData();
	}

	function showComment($params = array('code' => true)) {
		$out = '<div class="comment">';
		if (isset($this->data['Comment']['email'])) {
			$out .= '<div class="image">';
			$out .= $this->gravatar($this->data['Comment']['email']);
			$out .= '</div>';
		}

		$out .= '<div class="comment-by" style="font-size:120%;">';
		$name = $this->data['Comment']['name'];

		if (!empty($this->data['website'])) {
			$name = $this->Html->link($this->data['Comment']['name'],
				$this->Blog->formatUrl($this->data['Comment']['website']),
				array(
					'target' => '_blank'
					)
				);
		}

		$out .= sprintf('%s :: %s', $name, $this->Time-> {
				$this->dateFormat}
			($this->data['Comment']['created']));
		$out .= '</div>';
		$out .= '<div class="comment-comment">';
		if ($params['code']) {
			$out .= $this->Geshi->highlight($this->data['Comment']['comment']);
		}else {
			$out .= $this->data['Comment']['comment'];
		}

		$out .= '</div>';
		$out .= '</div>';

		$this->unsetData();

		return $out;
	}

	function addComment($post_id = null, $buttonText = 'Submit Comment') {
		if (!$post_id) {
			$post_id = $this->data['Comment']['post_id'];
		}
		if (!$post_id) {
			$this->errors[] = 'Please give me the id for the post you are commenting on';
			return false;
		}

		$out = '<fieldset>';
		$out .= '<legend>' . __('What do you think', true) . '?</legend>';
		$out .= $this->Form->create('Comment', $this->addCommentUrl);

		foreach($this->commentFields as $field) {
			if ($field != 'comment') {
				$out .= $this->Form->input($field);
			}else {
				$editor = (Configure::read('Wysiwyg.editor')) ? Configure::read('Wysiwyg.editor') : 'text';
				$out .= $this->Wysiwyg->$editor('Comment.comment', 'Simple');
			}
		}

		$out .= $this->Form->hidden('post_id', array('value' => $post_id));
		$out .= $this->Form->end(__($buttonText, true));
		$out .= '</fieldset>';

		return $out;
	}

	function countBox($counts = array()) {
		if (empty($counts)) {
			return false;
		}

		$out = '<strong class="h">' .
		$this->Html->link(
			__('Comments', true),
			array(
				'plugin' => 'blog',
				'controller' => 'comments'
				)
			) .
		'</strong>';
		$out .= '<div class="box">';
		$out .= '<ul>';
		if ($counts['active']) {
			$out .= '<li>' .
			$this->Html->link(
				__('Active', true) . ' ( ' . $counts['active'] . ' )',
				array(
					'plugin' => 'blog',
					'controller' => 'comments',
					'action' => 'index',
					1
					)
				) .
			'</li>';
		}

		if ($counts['pending']) {
			$out .= '<li>' .
			$this->Html->link(
				__('Pending', true) . ' ( ' . $counts['pending'] . ' )',
				array(
					'plugin' => 'blog',
					'controller' => 'comments',
					'action' => 'index',
					0
					)
				) .
			'</li>';
		}
		$out .= '</ul>';
		$out .= '</div>';

		return $out;
	}
}

?>