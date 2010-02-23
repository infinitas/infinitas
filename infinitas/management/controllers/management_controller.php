<?php
/**
* Comment Template.
*
* @todo Implement .this needs to be sorted out.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/
	class ManagementController extends ManagementAppController {
		var $name = 'Management';

		var $uses = array();

		var $helpers = array(
			'Google.Chart',
			'Libs.Gravatar'
		);

		function admin_dashboard() {
			$Comment = ClassRegistry::init('Management.Comment');
			$User    = ClassRegistry::init('Management.User');
			$data['latestComment'] = $Comment->latestComments();
			$data['latestUser']    = $User->latestUsers();

			$data['Count']['Comment']['active']  = $Comment->find('count', array('conditions' => array('Comment.active' => 1)));
			$data['Count']['Comment']['pending'] = $Comment->find('count', array('conditions' => array('Comment.active' => 0)));
			$data['Count']['Comment']['spam']    = $Comment->find('count', array('conditions' => array('Comment.status != ' => 'approved')));
			$data['Count']['Comment']['total']   = array_sum($data['Count']['Comment']);
			$data['Count']['User']['loggedIn']   = $User->loggedInUserCount();

			$Blog = ClassRegistry::init('Blog.Post');
			$data['Count']['Post']['active']  = $Blog->find('count', array('conditions' => array('Post.active' => 1)));
			$data['Count']['Post']['pending'] = $Blog->find('count', array('conditions' => array('Post.active' => 0)));
			$data['Count']['Post']['total']  = array_sum($data['Count']['Post']);

			$Content                             = ClassRegistry::init('Cms.Content');
			$data['Count']['Content']['active']  = $Content->find('count', array('conditions' => array('Content.active' => 1)));
			$data['Count']['Content']['pending'] = $Content->find('count', array('conditions' => array('Content.active' => 0)));
			$data['Count']['Content']['total'] = array_sum($data['Count']['Content']);

			switch(Configure::read('Website.admin_quick_post')){
				case 'cms':
					$categories = ClassRegistry::init('Cms.Category')->find('list');
					$groups     = ClassRegistry::init('Management.Group')->find('list');
					$layouts    = ClassRegistry::init('Cms.Layout')->find('list');
					$this->set(compact('categories', 'groups', 'layouts'));
					break;
				case 'blog':
					$categories = ClassRegistry::init('Blog.Category')->find('list');
					$this->set(compact('categories'));
					break;
			} // switch

			$this->set(compact('data'));
		}
	}
?>