<?php
	class EmailsEvents extends AppEvents {
		public function onRequireDatabaseConfigs(Event $Event) {
			return array(
				'emails' => array(
					'datasource' => 'Emails.EmailSource'
				)
			);
		}

		public function onPluginRollCall(Event $Event) {
			return array(
				'name' => 'Emails',
				'description' => 'Manage your mails',
				'author' => 'Infinitas',
				'icon' => 'envelope',
				'dashboard' => array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'dashboard')
			);
		}

		public function onAdminMenu(Event $Event) {
			$menu['main'] = array(
				'Dashboard' => array('controller' => 'mail_systems', 'action' => 'dashboard'),
				'Accounts' => array('controller' => 'email_accounts', 'action' => 'index'),
			);

			return $menu;
		}

		public function onSlugUrl(Event $Event, $data = null, $type = null) {
			switch($data['type']) {
				case 'inbox':
					return array(
						'plugin' => 'emails',
						'controller' => 'mail_systems',
						'action' => 'index',
						'slug' => $data['data']['EmailAccount']['slug'],
						'account' => $data['data']['EmailAccount']['id']
					);
					break;

				case 'view':
					return array(
						'plugin' => 'emails',
						'controller' => 'mail_systems',
						'action' => 'view',
						'slug' => $data['data']['EmailAccount']['slug'],
						'account' => $data['data']['EmailAccount']['id'],
						'email' => $data['data']['MailSystem']['id'],
						'subject' => $data['data']['MailSystem']['slug']
					);
					break;

				case 'mail':
					return array(
						'plugin' => 'emails',
						'controller' => 'mail_systems',
						'action' => 'admin_get_mail',
						'slug' => $data['data']['EmailAccount']['slug'],
						'account' => $data['data']['EmailAccount']['id'],
						'email' => $data['data']['MailSystem']['id']
					);
					break;

				default:
					pr($data);
					exit;
					break;
			}
		}

		public function onSetupRoutes(Event $Event) {
			// dashboard
			InfinitasRouter::connect(
				'/admin/mail',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'dashboard', 'admin' => true)
			);
			// mail render
			InfinitasRouter::connect(
				'/admin/mail/:slug/:account/:email',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'get_mail', 'admin' => true),
				array('pass' => array('slug', 'account', 'email'))
			);
			// view
			InfinitasRouter::connect(
				'/admin/inbox/:slug/:account/:email/:subject',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'view', 'admin' => true),
				array('pass' => array('slug', 'account', 'email'))
			);

			// inbox
			InfinitasRouter::connect(
				'/admin/inbox/:slug/:account',
				array('plugin' => 'emails', 'controller' => 'mail_systems', 'action' => 'index', 'admin' => true),
				array('pass' => array('slug', 'account'))
			);
		}

		public function onRunCrons(Event $Event) {
			return false;
			$accounts = ClassRegistry::init('Emails.EmailAccount')->getCronAccounts();

			foreach ($accounts as $account) {
				if (!isset($account['EmailAccount']) || empty($account['EmailAccount'])) {
					continue;
				}

				$this->_dispatchMails(
					$Event,
					ClassRegistry::init('Emails.MailSystem')->checkNewMail($account['EmailAccount'])
				);
			}

			return true;
		}

		protected function _dispatchMails(Event $Event, $mails) {
			foreach ($mails as $mail) {
				EventCore::trigger($Event, 'receiveMails', $mail);
			}
		}
	}
