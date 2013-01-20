<?php
/**
 * NewslettersController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * NewslettersController
 *
 * @package Infinitas.Newsletter.Controller
 */

class NewslettersController extends NewsletterAppController {

/**
 * BeforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->notice['sent'] = array(
			'message' => 'Your message has been sent',
			'redirect' => ''
		);

		$this->notice['no_campaigns'] = array(
			'message' => __d('newsletter', 'Please create a campaign before creating a newsletter'),
			'level' => 'notice',
			'redirect' => array(
				'controller' => 'campaigns'
			)
		);
	}

/**
 * Contact form
 *
 * @return void
 */
	public function contact() {
		if (!empty($this->request->data)) {
			$this->request->data[$this->modelClass]['ip_address'] = $this->Auth->user('ip_address');
			$this->request->data[$this->modelClass]['query'] = htmlspecialchars($this->request->data[$this->modelClass]['query']);
			$this->Event->trigger('adminEmail', array(
				'email' => array('newsletter' => 'newsletter-contact-admin'),
				'var' => array('Newsletter' => $this->request->data[$this->modelClass])
			));
			
			$this->notice('sent');
		}

		$this->saveRedirectMarker();

		$this->request->data[$this->modelClass]['name'] = $this->Auth->user('username');
		$this->request->data[$this->modelClass]['email'] = $this->Auth->user('email');
	}

/**
 * Newsletter tracking
 *
 * Basic newsletter tracking by rendering a 1x1 image through php and tracking
 * loads
 *
 * @param string $id newsletter id
 */
	public function track($id) {
		Configure::write('debug', 0);
		$this->autoRender = false;
		$this->layout = 'ajax';

		if (!$id) {
			$this->log('no id for email tracking', $this->modelClass);
			exit;
		}

		$this->{$this->modelClass}->id = $id;
		$views = $this->{$this->modelClass}->read('views');

		if (empty($views)) {
			$this->log('no newsletter found with id: ' . $id);
			exit;
		}

		if (!$this->{$this->modelClass}->saveField('views', $views[$this->modelClass]['views'] + 1)) {
			$this->log('could not save a view for id: ' . $id);
		}
		exit;
	}

/**
 * Send an email
 *
 * @return void
 */
	public function sendEmail() {
		$this->autoRender = false;
		$info = array_merge(
			array(
				'to' => array(),
				'cc' => array(),
				'bcc' => array(),
				'subject' => null,
				'html' => null,
				'text' => null
			),
			$this->request->params['named']['email']
		);

		$this->Emailer->to  = $info['to'];
		$this->Emailer->bcc = $info['bcc'];
		$this->Emailer->subject = $info['subject'];
		$this->Emailer->template = 'blank';
		$this->set('info', $info);

		$this->Emailer->delivery = 'smtp';

		$this->Emailer->send($info['html']);
	}

/**
 * Send newsletters
 *
 * @return void
 */
	public function sendNewsletters() {
		$this->autoRender = false;
		$this->layout = 'ajax';
		Configure::write('debug', 0);

		$newsletters = $this->{$this->modelClass}->find('toSend');

		foreach ($newsletters as $newsletter) {
			if (empty($newsletter['User'])) {
				continue;
			}

			$html = $newsletter['Template']['header'] . $newsletter[$this->modelClass]['html'] . $newsletter['Template']['footer'];

			$search = array(
				'<br/>',
				'<br>',
				'</p><p>'
			);

			$text = strip_tags(
				str_replace($search,
					"\n\r",
					$newsletter['Template']['header'] . $newsletter[$this->modelClass]['html'] . $newsletter['Template']['footer']
				)
			);

			foreach ($newsletter['User'] as $user) {
				$to = $user['email'];
				$name = $user['username'];
				//  @todo send the email here
				if (false) {
					$this->{$this->modelClass}->NewslettersUser->id = $user['NewslettersUser']['id'];
					if (!$this->{$this->modelClass}->NewslettersUser->saveField('sent', 1)) {
						$this->log('problem sending mail #' . $newsletter[$this->modelClass]['id'] . ' to user #' . $user['id'], $this->modelClass);
					}

					$this->{$this->modelClass}->id = $newsletter[$this->modelClass]['id'];
					if (!$this->{$this->modelClass}->saveField('sends', $newsletter[$this->modelClass]['sends'] + 1)) {
						$this->log('problem counting send for mail #' . $newsletter[$this->modelClass]['id'], $this->modelClass);
					}
				}
			}
		}
	}

/**
 * Newsletter dashboard
 *
 * @return void
 */
	public function admin_dashboard() {
		$hasCampaign = (bool)$this->{$this->modelClass}->NewsletterCampaign->find('count');
		$hasTemplate = (bool)$this->{$this->modelClass}->NewsletterTemplate->find('count');
		$hasNewsletter = (bool)$this->{$this->modelClass}->find('count');

		$this->set(compact('hasCampaign', 'hasTemplate', 'hasNewsletter'));
	}

/**
 * Newsletter reporting
 *
 * @param string $id
 *
 * @return void
 */
	public function admin_report($id) {
		if (!$id) {
			$this->notice('invalid');
		}
	}

/**
 * List all newsletters
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array('paginated');
		$newsletters = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'subject',
			'html',
			'from',
			'reply_to'
		);

		$this->set(compact('newsletters', 'filterOptions'));
	}

/**
 * Create a newsletter
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->isPost()) {
			$this->request->data[$this->modelClass]['newsletter_template_id'] = $this->{$this->modelClass}->NewsletterCampaign->field('newsletter_template_id', array(
				'NewsletterCampaign.id' => $this->request->data[$this->modelClass]['campaign_id']
			));
		}

		parent::admin_add();

		$campaigns = $this->{$this->modelClass}->NewsletterCampaign->find('list');
		if (empty($campaigns)) {
			$this->notice('no_campaigns');
		}

		$this->set(compact('campaigns'));
	}

/**
 * View a newsletter
 *
 * @param string $id the newsletter id to view
 *
 * @return void
 */
	public function admin_view($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->notice('invalid');
		}

		$newsletter = $this->{$this->modelClass}->read(null, $id);
		$template = $this->{$this->modelClass}->NewsletterTemplate->read(null, $newsletter[$this->modelClass]['newsletter_template_id']);

		if (!empty($this->request->data)) {
			$id = $this->request->data[$this->modelClass]['id'];

			$addresses = explode(',', $this->request->data[$this->modelClass]['email_addresses']);
			if (empty($addresses)) {
				$this->notice(
					__d('newsletter', 'Please input at least one email address for testing'),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}

			$sent = 0;
			foreach ($addresses as $address) {
				$email = new InfinitasEmail('gmail');
				$email->from(array($newsletter[$this->modelClass]['from'] => 'Infinitas'));
				$email->to($address);

				$email->subject(strip_tags($newsletter[$this->modelClass]['subject']));

				if ($email->send($template['Template']['header'] . $newsletter[$this->modelClass]['html'] . $template['Template']['footer'])) {
					$sent++;
				}
			}

			$this->notice(sprintf(__d('newsletter', '%s mails were sent'), $sent));
		}

		if (empty($this->request->data) && $id) {
			$this->request->data = $newsletter;
		}

		$this->set('newsletter', $this->{$this->modelClass}->read(null, $id));
	}

/**
 * Edit a newsletter
 *
 * @param string $id the newsletter id
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit();

		$this->set('campaigns', $this->{$this->modelClass}->NewsletterCampaign->find('list'));
	}

/**
 * Preview a newsletter
 *
 * @param string $id the newsletter id
 *
 * @return void
 */
	public function admin_preview($id = null) {
		$this->layout = 'ajax';
		Configure::write('debug', 0);

		try {
			$newsletter = $this->{$this->modelClass}->find('preview', $id);
			$this->set('newsletter', $newsletter);
		} catch (Exception $e) {
			$this->notice($e);
		}
	}

/**
 * Handle mass deletes
 *
 * @param array $ids list of ids to delete
 *
 * @return boolean
 */
	public function __massActionDelete($ids) {
		try {
			$ids = $this->{$this->modelClass}->find('deleteable', array(
				'ids' => $ids
			));
		} catch (Exception $e) {
			$this->notice($e);
		}
		return $this->MassAction->delete($ids);
	}

/**
 * Toggle and send newsletters
 *
 * @param string $id the newsletter id to toggle
 *
 * @return void
 */
	public function admin_toggleSend($id = null) {
		try {
			if ($this->{$this->modelClass}->toggleSend($id)) {
				return $this->notice(
					__d('newsletter', 'Newsletter is now sending'),
					array(
						'redirect' => true
					)
				);
			}
		} catch (Exception $e) {
			$this->notice($e);
		}
	}

/**
 * Stop newsletter sending
 *
 * @return void
 */
	public function admin_stopAll() {
		if ($this->{$this->modelClass}->stopAllSending()) {
			return $this->notice(__d('newsletter', 'All newsletters have been stopped'), array(
				'redirect' => true
			));
		}

		$this->notice(__d('newsletter', 'There was a problem stopping some emails'), array(
			'redirect' => true
		));
	}

/**
 * Mass Action overload
 *
 * @return void
 */
	public function admin_mass() {
		if ($this->MassAction->getAction() == 'send') {
			$ids = $this->MassAction->getIds($this->MassAction->getAction(), $this->request->data[$this->modelClass]);
			$ids = $this->{$this->modelClass}->find('list', array(
				'conditions' => array(
					$this->{$this->modelClass}->alias . '.active' => 0,
					$this->{$this->modelClass}->alias . '.' . $this->{$this->modelClass}->primaryKey => $ids
				)
			));

			if (empty($ids)) {
				$this->notice(__d('newsletter', 'Nothing to send'), array(
					'level' => 'warning',
					'redirect' => ''
				));
			}

			$message = 'The Newsletters are now sending';
			if (count($ids) === 1) {
				$message = 'Newsletter is now sending';
			}

			$this->MassAction->toggle(array_keys($ids), $message);
		}

		parent::admin_mass();
	}
}