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

	class CampaignsController extends NewsletterAppController {
		public $name = 'Campaigns';

		public function admin_index() {
			$this->paginate = array(
				'fields' => array(
					'Campaign.id',
					'Campaign.name',
					'Campaign.description',
					'Campaign.newsletter_count',
					'Campaign.active',
					'Campaign.created',
					'Campaign.modified'
				),
				'Campaign' => array(
					'contain' => array(
						'Template' => array(
							'fields' => array(
								'Template.id',
								'Template.name'
							)
						),
						'Newsletter' => array(
							'fields' => array(
								'Newsletter.sent'
							)
						)
					)
				)
			);

			$campaigns = $this->paginate('Campaign', $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'description'
			);

			$this->set(compact('campaigns','filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			$templates = $this->Campaign->Template->find('list');
			if(empty($templates)){
				$this->notice(
					__('Please create a template before creating your campaigns', true),
					array(
						'level' => 'notice',
						'redirect' => array(
							'controller' => 'templates'
						)
					)
				);
			}
			
			$newsletters = $this->Campaign->Newsletter->find('list');
			$this->set(compact('templates', 'newsletters'));
		}

		public function admin_edit($id) {
			parent::admin_edit($id);

			$templates = $this->Campaign->Template->find('list');
			$newsletters = $this->Campaign->Newsletter->find('list');
			$this->set(compact('templates', 'newsletters'));
		}

		public function admin_toggle($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('Please select a campaign', true));
				$this->redirect($this->referer());
			}

			$data = $this->Campaign->find(
				'first',
				array(
					'fields' => array(
						'Campaign.id',
						'Campaign.active',
					),
					'conditions' => array(
						'Campaign.id' => $id
					),
					'contain' => array(
						'Newsletter' => array(
							'fields' => array(
								'Newsletter.id'
							)
						)
					)
				)
			);

			if (!$data['Campaign']['active'] && empty($data['Newsletter'])) {
				$this->Session->setFlash(__('You can not enable a campaign with no mails.', true));
				$this->redirect($this->referer());
			}

			return parent::admin_toggle($id);
		}

		public function __massActionDelete($ids){
			return $this->MassAction->delete($this->__canDelete($ids));
		}

		private function __canDelete($ids) {
			$newsletters = $this->Campaign->Newsletter->find(
				'list',
				array(
					'fields' => array(
						'Newsletter.campaign_id',
						'Newsletter.campaign_id'
					),
					'conditions' => array(
						'Newsletter.sent' => 1,
						'Newsletter.campaign_id' => $ids
					)
				)
			);

			if (empty($newsletters)) {
				return $ids;
			}

			foreach($ids as $k => $v) {
				if (isset($newsletters[$v])) {
					unset($ids[$k]);
				}
			}

			if (!empty($ids)) {
				return $ids;
			}

			$this->Session->setFlash(__('None of the campaigns you selected are deletable.', true));
			$this->redirect($this->referer());
		}
	}