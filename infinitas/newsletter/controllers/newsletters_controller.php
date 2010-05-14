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

	class NewslettersController extends NewsletterAppController {
		/**
		* Class name.
		*
		* @access public
		* @var string
		*/
		var $name = 'Newsletters';

		/**
		* Helpers.
		*
		* @access public
		* @var array
		*/
		var $helpers = array(
			'Filter.Filter'
		);

		function beforeFilter() {
			parent::beforeFilter();
			//  @todo make sure function track is allowed by all
			$this->Auth->allow('track');
		}

		function track($id) {
			Configure::write('debug', 0);
			$this->autoRender = false;
			$this->layout = 'ajax';

			if (!$id) {
				$this->log('no id for email tracking', 'newsletter');
				exit;
			}

			$this->Newsletter->id = $id;
			$views = $this->Newsletter->read('views');

			if (empty($views)) {
				$this->log('no newsletter found with id: ' . $id);
				exit;
			}

			if (!$this->Newsletter->saveField('views', $views['Newsletter']['views'] + 1)) {
				$this->log('could not save a view for id: ' . $id);
			}
			exit;
		}

		function sendNewsletters() {
			$this->autoRender = false;
			$this->layout = 'ajax';
			Configure::write('debug', 0);

			$newsletters = $this->Newsletter->find(
				'all',
				array(
					'fields' => array(
						'Newsletter.id',
						'Newsletter.html',
						'Newsletter.text',
						'Newsletter.sends'
					),
					'conditions' => array(
						'Newsletter.sent' => 0,
						'Newsletter.active' => 1,
					),
					'contain' => array(
						'Template' => array(
							'fields' => array(
								'Template.header',
								'Template.footer',
							)
						),
						'User' => array(
							'fields' => array(
								'User.id',
								'User.email',
								'User.username'
							),
							'conditions' => array(
								'NewslettersUser.sent' => 0
							)
						)
					)
				)
			);

			foreach($newsletters as $newsletter) {
				if (empty($newsletter['User'])) {
					continue;
				}

				$html = $newsletter['Template']['header'] . $newsletter['Newsletter']['html'] . $newsletter['Template']['footer'];

				$search = array(
					'<br/>',
					'<br>',
					'</p><p>'
				);

				$text = strip_tags(
					str_replace($search,
						"\n\r",
						$newsletter['Template']['header'] . $newsletter['Newsletter']['html'] . $newsletter['Template']['footer']
					)
				);

				foreach($newsletter['User'] as $user) {
					$to = $user['email'];
					$name = $user['username'];
					//  @todo send the email here
					if (false) {
						$this->Newsletter->NewslettersUser->id = $user['NewslettersUser']['id'];
						if (!$this->Newsletter->NewslettersUser->saveField('sent', 1)) {
							$this->log('problem sending mail #' . $newsletter['Newsletter']['id'] . ' to user #' . $user['id'], 'newsletter');
						}

						$this->Newsletter->id = $newsletter['Newsletter']['id'];
						if (!$this->Newsletter->saveField('sends', $newsletter['Newsletter']['sends'] + 1)) {
							$this->log('problem counting send for mail #' . $newsletter['Newsletter']['id'], 'newsletter');
						}
					}
				}
			}
		}

		function admin_dashboard() {
		}

		function admin_report($id) {
			if (!$id) {
				$this->Session->setFlash(__('Please select a newsletter', true));
				$this->redirect($this->referer());
			}
		}

		function admin_index() {
			$this->paginate = array(
				'fields' => array(
					'Newsletter.id',
					'Newsletter.campaign_id',
					'Newsletter.from',
					'Newsletter.reply_to',
					'Newsletter.subject',
					'Newsletter.active',
					'Newsletter.sent',
					'Newsletter.created',
					),
				'limit' => 20,
				'contain' => array(
					'Campaign' => array(
						'fields' => array(
							'Campaign.name'
						)
					)
				)
			);

			$newsletters = $this->paginate('Newsletter', $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'subject',
				'html',
				'from',
				'reply_to'
			);

			$this->set(compact('newsletters','filterOptions'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->Newsletter->create();
				if ($this->Newsletter->save($this->data)) {
					$this->Session->setFlash(__('Your newsletter has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}
			}

			$campaigns = $this->Newsletter->Campaign->find('list');
			$this->set(compact('campaigns'));
		}

		function admin_edit() {
		}

		function admin_view($id = null) {
			if (!$id && empty($this->data)) {
				$this->Session->setFlash(__('Please select a newsletter', true));
				$this->redirect($this->referer());
			}

			$newsletter = $this->Newsletter->read(null, $id);

			if (!empty($this->data)) {
				$id = $this->data['Newsletter']['id'];

				$addresses = explode(',', $this->data['Newsletter']['email_addresses']);
				if (empty($addresses)) {
					$this->Session->setFlash(__('Please input at least one email address for testing.', true));
					$this->redirect($this->referer());
				}

				$sent = 0;
				foreach($addresses as $address) {
					$this->Email->from = 'Infinitas Test Mail <' . $newsletter['Newsletter']['from'] . '>';
					$this->Email->to = 'Test <' . $address . '>';

					$this->Email->subject = strip_tags($newsletter['Newsletter']['subject']);
					$this->set('email', $newsletter['Template']['header'] . $newsletter['Newsletter']['html'] . $newsletter['Template']['footer']);

					if ($this->Email->send()) {
						$sent++;
					}

					pr($this->Email->smtpError);

					$this->Email->reset();
				}
				$this->Session->setFlash(sprintf('%s %s' , $sent, __('mails were sent', true)));
			}

			if (empty($this->data) && $id) {
				$this->data = $newsletter;
			}

			$this->set('newsletter', $this->Newsletter->read(null, $id));
		}

		function admin_preview($id = null) {
			$this->layout = 'ajax';

			if (!$id) {
				$this->set('data', __('The template was not found', true));
			}else {
				$newsletter = $this->Newsletter->find(
					'first',
					array(
						'fields' => array(
							'Newsletter.id',
							'Newsletter.html'
						),
						'conditions' => array(
							'Newsletter.id' => $id
						),
						'contain' => array(
							'Template' => array(
								'fields' => array(
									'Template.header',
									'Template.footer',
								)
							)
						)
					)
				);

				$this->set('data', $newsletter['Template']['header'] . $newsletter['Newsletter']['html'] . $newsletter['Template']['footer']);
			}
		}

		function admin_delte() {
			return false;
		}

		function __massActionDelete($ids)
		{
			return $this->MassAction->delete($this->__canDelete($ids));
		}

		function __canDelete($ids) {
			$newsletters = $this->Newsletter->find(
				'list',
				array(
					'fields' => array(
						'Newsletter.id',
						'Newsletter.id'
					),
					'conditions' => array(
						'Newsletter.sent' => 0, // only get mails that are not sent
						'Newsletter.sends > ' => 0, // get mails that have not sent anything.
						'Newsletter.id' => $ids
					)
				)
			);

			if (empty($newsletters)) {
				$this->Session->setFlash(__('There are no newsletters to delete.', true));
				$this->redirect($this->referer());
			}
			return $newsletters;
		}

		function admin_toggleSend($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('Please select a newsletter', true));
				$this->redirect($this->referer());
			}

			$this->Newsletter->recursive = - 1;
			$sent = $this->Newsletter->read(array('id', 'sent', 'active'), $id);

			if (!isset($sent['Newsletter']['sent'])) {
				$this->Session->setFlash(__('The newsletter was not found', true));
				$this->redirect($this->referer());
			}

			if ($sent['Newsletter']['sent']) {
				$this->Session->setFlash(__('The newsletter has already been sent', true));
				$this->redirect($this->referer());
			}

			if (!$sent['Newsletter']['active']) {
				$sent['Newsletter']['active'] = 1;

				if (!$this->Newsletter->save($sent)) {
					$this->Session->setFlash(__('Could not activate the newsletter', true));
					$this->redirect($this->referer());
				}
			}

			$this->Session->setFlash(__('Newsletter is now sending.', true));
			$this->redirect($this->referer());
		}

		function admin_stopAll() {
			$runningNewsletters = $this->Newsletter->find(
				'list',
				array(
					'fields' => array(
						'Newsletter.id',
						'Newsletter.id'
					),
					'conditions' => array(
						'Newsletter.active' => 1,
						'Newsletter.sent' => 0
					),
					'contain' => false
				)
			);

			foreach($runningNewsletters as $id) {
				$this->Newsletter->id = $id;
				$this->Newsletter->saveField('active', 0);
			}

			$this->Session->setFlash(__('All newsletters have been stopped.', true));
			$this->redirect($this->referer());
		}
	}