<?php
/**
 * CampaignsController
 *
 * @package Infinitas.Newsletter.Controller
 */

/**
 * CampaignsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Newsletter.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class NewsletterCampaignsController extends NewsletterAppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->notice['no_templates'] = array(
			'message' => __d('newsletter', 'Please create a template before creating your campaigns'),
			'level' => 'notice',
			'redirect' => ''
		);
	}
/**
 * View all campaigns
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array('paginated');

		$newsletterCampaigns = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'description',
			'Template.name'
		);

		$this->set(compact('newsletterCampaigns', 'filterOptions'));
	}

/**
 * Create a new campaign
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		$newsletterTemplates = $this->{$this->modelClass}->NewsletterTemplate->find('list');
		if (empty($newsletterTemplates)) {
			$this->notice('no_templates');
		}

		$newsletters = $this->{$this->modelClass}->Newsletter->find('list');
		$this->set(compact('newsletterTemplates', 'newsletters'));
	}

/**
 * Edit a campaign
 *
 * @param string $id the record to edit
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

		$newsletterTemplates = $this->{$this->modelClass}->NewsletterTemplate->find('list');
		if (empty($newsletterTemplates)) {
			$this->notice('no_templates');
		}
		$newsletters = $this->{$this->modelClass}->Newsletter->find('list');
		$this->set(compact('newsletterTemplates', 'newsletters'));
	}

/**
 * Toggle a campaign
 *
 * @param string $id the campaign id to toggle
 *
 * @return void
 */
	public function admin_toggle($id = null) {
		if (!$id) {
			$this->notice('invalid');
		}

		$data = $this->{$this->modelClass}->find('first', array(
			'fields' => array(
				$this->modelClass . '.id',
				$this->modelClass . '.active',
			),
			'conditions' => array(
				$this->modelClass . '.id' => $id
			),
			'contain' => array(
				'Newsletter' => array(
					'fields' => array(
						'Newsletter.id'
					)
				)
			)
		));

		if (!$data[$this->modelClass]['active'] && empty($data['Newsletter'])) {
			$this->notice(
				__d('newsletter', 'You can not enable a campaign with no mails.'),
				array(
					'level' => 'warning',
					'redirect' => true
				)
			);
		}

		return parent::admin_toggle($id);
	}

/**
 * Handle the mass action deletes
 *
 * @param array $ids the ids to be deleted
 *
 * @return array
 */
	public function __massActionDelete($ids) {
		return $this->MassAction->delete($this->__canDelete($ids));
	}

/**
 * Check if the campaign can be deleted
 *
 * @param array $ids
 *
 * @return array
 */
	private function __canDelete($ids) {
		$newsletters = $this->{$this->modelClass}->Newsletter->find('list', array(
			'fields' => array(
				'Newsletter.newsletter_campaign_id',
				'Newsletter.newsletter_campaign_id'
			),
			'conditions' => array(
				'Newsletter.sent' => 1,
				'Newsletter.newsletter_campaign_id' => $ids
			)
		));

		if (empty($newsletters)) {
			return $ids;
		}

		foreach ($ids as $k => $v) {
			if (isset($newsletters[$v])) {
				unset($ids[$k]);
			}
		}

		if (!empty($ids)) {
			return $ids;
		}

		$this->notice(
			__d('newsletter', 'None of the campaigns you selected are deletable.'),
			array(
				'level' => 'warning',
				'redirect' => true
			)
		);
	}

}